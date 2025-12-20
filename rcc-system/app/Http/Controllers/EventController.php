<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{
    public function index()
    {
        $events = new LengthAwarePaginator([], 0, 12);
        if (Schema::hasTable('events')) {
            $q = Event::query();
            if (Schema::hasColumn('events', 'status') || Schema::hasColumn('events', 'is_active')) {
                $q = Event::active();
            }
            $category = request()->string('category')->toString();
            $location = request()->string('location')->toString();
            $dateFrom = request()->string('date_from')->toString();
            $dateTo = request()->string('date_to')->toString();
            if ($category) {
                $q->where('category', $category);
            }
            if ($location) {
                $q->where('location', 'like', "%{$location}%");
            }
            if (Schema::hasColumn('events', 'start_date')) {
                if ($dateFrom) {
                    $q->whereDate('start_date', '>=', $dateFrom);
                } else {
                    $q->whereDate('start_date', '>=', now()->toDateString());
                }
                if ($dateTo) {
                    $q->whereDate('start_date', '<=', $dateTo);
                }
                $q->orderBy('start_date');
            } else {
                $q->orderBy('id');
            }
            $events = $q->paginate(12)->withQueryString();
        }

        if (request()->wantsJson()) {
            $items = collect($events->items())->map(function (Event $e) {
                $photo = $e->featured_image ?? (is_array($e->photos ?? null) && count($e->photos) ? $e->photos[0] : null);

                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'photo_thumb_url' => $photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($photo) : null,
                ];
            })->all();

            return response()->json([
                'events' => $items,
                'nextPageUrl' => $events->nextPageUrl(),
            ]);
        }

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        if (! $event->isActive()) {
            abort(404);
        }

        $user = Auth::user();
        $hasTicket = false;
        $participants = collect();

        if ($user) {
            $hasTicket = $event->participations()
                ->where('user_id', $user->id)
                ->where('payment_status', 'approved')
                ->exists();
        }

        $participants = $event->participations()
            ->with('user')
            ->whereIn('payment_status', ['approved', 'pending'])
            ->latest()
            ->take(24)
            ->get();

        return view('events.show', compact('event', 'user', 'hasTicket', 'participants'));
    }

    public function purchase(Event $event)
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect' => route('events.show', $event)])
                ->with('warning', 'Por favor, faça login para participar do evento.');
        }

        if (! $event->isActive()) {
            return redirect()->back()->with('error', 'Este evento não está disponível.');
        }

        if ($event->isSoldOut()) {
            return redirect()->back()->with('error', 'Ingressos esgotados!');
        }

        // Verificar se já possui ingresso ativo
        $existingTicket = $event->tickets()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if ($existingTicket) {
            return redirect()->route('dashboard.tickets.show', $existingTicket)
                ->with('info', 'Você já possui um ingresso para este evento!');
        }

        // Redirecionar para a página de compra do evento
        return redirect()->route('events.purchase', $event);
    }
}
