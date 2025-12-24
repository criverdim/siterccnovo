<?php

namespace App\Services;

use App\Models\EventParticipation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Generator;

class TicketService
{
    public function generateAndSend(EventParticipation $participation): void
    {
        $uuid = $participation->ticket_uuid ?? (string) \Illuminate\Support\Str::uuid();
        $qrData = 'TICKET:'.$uuid;
        $qr = (new Generator)->size(300)->format('png')->generate($qrData);

        $qrRelativePath = 'tickets/qr_'.$uuid.'.png';
        Storage::disk('local')->put($qrRelativePath, $qr);
        $qrAbsolutePath = Storage::disk('local')->path($qrRelativePath);

        $pdf = Pdf::loadView('tickets.pdf', [
            'participation' => $participation,
            'qrPath' => $qrAbsolutePath,
        ]);
        $pdfRelativePath = 'tickets/ticket_'.$uuid.'.pdf';
        Storage::disk('local')->put($pdfRelativePath, $pdf->output());
        $pdfAbsolutePath = Storage::disk('local')->path($pdfRelativePath);

        $participation->update([
            'ticket_uuid' => $uuid,
            'ticket_qr_hash' => hash('sha256', $qrData),
        ]);

        // Criar/atualizar registro de Ticket para uso no check-in
        $payment = \App\Models\Payment::where('user_id', $participation->user_id)
            ->where('event_id', $participation->event_id)
            ->latest()
            ->first();

        $ticket = \App\Models\Ticket::updateOrCreate(
            ['ticket_code' => $uuid],
            [
                'user_id' => $participation->user_id,
                'event_id' => $participation->event_id,
                'payment_id' => $payment?->id,
                'qr_code' => $qrAbsolutePath,
                'status' => 'active',
                'pdf_path' => $pdfAbsolutePath,
            ]
        );

        \Mail::to($participation->user->email)->send(new \App\Mail\TicketMailable($participation, $pdfAbsolutePath));

        \App\Models\WaMessage::create([
            'user_id' => $participation->user_id,
            'message' => 'Seu ingresso foi gerado.',
            'payload' => ['ticket_pdf' => $pdfAbsolutePath],
            'status' => 'pending',
        ]);
    }

    public function generateAndSendTicket(EventParticipation $participation): void
    {
        $this->generateAndSend($participation);
    }
}
