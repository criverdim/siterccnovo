<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private function mp()
    {
        return app('\\App\\Services\\MercadoPagoService');
    }

    public function purchase(Event $event)
    {
        if (! $event->isActive()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Este evento não está disponível para compra.');
        }

        if ($event->availableTickets() <= 0) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Ingressos esgotados para este evento.');
        }

        return view('events.purchase', compact('event'));
    }

    public function processPayment(Request $request, Event $event)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'email' => 'required|email',
        ]);

        if (! config('services.mercadopago.access_token')) {
            return response()->json([
                'success' => false,
                'error' => 'Pagamento indisponível: configure MERCADOPAGO_ACCESS_TOKEN.',
            ], 422);
        }

        if (! $event->isActive()) {
            return response()->json([
                'success' => false,
                'error' => 'Evento não disponível para compra.',
            ], 400);
        }

        $quantity = $request->input('quantity');

        if ($event->availableTickets() < $quantity) {
            return response()->json([
                'success' => false,
                'error' => 'Quantidade de ingressos indisponível.',
            ], 400);
        }

        try {
            $result = $this->mp()->createPaymentPreference(
                $event,
                $quantity,
                $request->input('email')
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'preference_id' => $result['preference_id'],
                    'init_point' => $result['init_point'],
                    'payment_id' => $result['payment']->id,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Erro ao processar pagamento.',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento', [
                'event_id' => $event->id,
                'quantity' => $quantity,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao processar pagamento. Tente novamente.',
            ], 422);
        }
    }

    public function success(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        // Verificar status do pagamento
        if ($payment->status === 'pending') {
            // Validar com Mercado Pago
            $validation = $this->mp()->validatePayment((string) $payment->mercado_pago_id);

            if ($validation['success'] && $validation['data']['status'] === 'approved') {
                $payment->update([
                    'status' => 'paid',
                    'payment_status' => 'approved',
                ]);
            }
        }

        $event = $payment->event;
        $tickets = $payment->tickets;

        return view('events.payment-success', compact('payment', 'event', 'tickets'));
    }

    public function failure(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $payment->update([
            'status' => 'failed',
            'payment_status' => 'rejected',
        ]);

        $event = $payment->event;

        return view('events.payment-failure', compact('payment', 'event'));
    }

    public function pending(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $event = $payment->event;

        return view('events.payment-pending', compact('payment', 'event'));
    }

    public function webhook(Request $request)
    {
        Log::info('Webhook Mercado Pago recebido', [
            'method' => $request->method(),
            'data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        try {
            $data = $request->all();

            // Validar origem do webhook (opcional, mas recomendado)
            // if (!$this->validateWebhookOrigin($request)) {
            //     Log::warning('Webhook com origem inválida', ['request' => $request->all()]);
            //     return response()->json(['error' => 'Origem inválida'], 403);
            // }

            $topic = $data['topic'] ?? $data['type'] ?? null;

            if ($topic === 'payment' || (isset($data['data']) && isset($data['data']['id']))) {
                $success = $this->mp()->processWebhook($data);

                if ($success) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['error' => 'Erro ao processar webhook'], 500);
                }
            }

            return response()->json(['success' => true, 'message' => 'Tópico não processado']);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json(['error' => 'Erro interno'], 500);
        }
    }

    public function myTickets()
    {
        if (! auth()->check()) {
            return redirect('/login');
        }
        $participations = \App\Models\EventParticipation::with('event')
            ->where('user_id', auth()->id())
            ->where('payment_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('events.my-tickets', compact('participations'));
    }

    public function downloadTicket(Payment $payment, $ticketId)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket = $payment->tickets()->findOrFail($ticketId);

        // Gerar PDF do ingresso
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('events.ticket-pdf', compact('ticket'));

        return $pdf->download("ingresso-{$ticket->ticket_code}.pdf");
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = Ticket::with(['event', 'user'])
            ->where('code', $request->input('ticket_code'))
            ->first();

        if (! $ticket) {
            return response()->json([
                'success' => false,
                'error' => 'Ingresso não encontrado.',
            ], 404);
        }

        if ($ticket->status !== 'active') {
            return response()->json([
                'success' => false,
                'error' => 'Ingresso inválido ou já utilizado.',
            ], 400);
        }

        if ($ticket->event->start_date->isPast()) {
            return response()->json([
                'success' => false,
                'error' => 'Evento já finalizado.',
            ], 400);
        }

        // Realizar check-in
        $ticket->update([
            'status' => 'used',
            'checkin_at' => now(),
        ]);

        // Criar registro de check-in
        $ticket->checkins()->create([
            'checked_at' => now(),
            'checked_by' => auth()->id(),
            'location' => $request->input('location', 'Entrada Principal'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in realizado com sucesso!',
            'ticket' => [
                'code' => $ticket->code,
                'event_name' => $ticket->event->name,
                'user_name' => $ticket->user->name,
                'checkin_at' => $ticket->checkin_at->format('d/m/Y H:i:s'),
            ],
        ]);
    }
}
