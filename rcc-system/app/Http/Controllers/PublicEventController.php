<?php

namespace App\Http\Controllers;

use App\Models\Event;

class PublicEventController extends Controller
{
    public function index()
    {
        $q = request()->string('q')->toString();
        $paid = request()->input('paid');
        $month = request()->integer('month');

        try {
            $events = Event::query()
                ->where('is_active', true)
                ->when($q, fn ($qr) => $qr->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%$q%")
                        ->orWhere('description', 'like', "%$q%")
                        ->orWhere('location', 'like', "%$q%");
                }))
                ->when(in_array($paid, ['paid', 'free'], true), fn ($qr) => $qr->where('is_paid', $paid === 'paid'))
                ->when($month && $month >= 1 && $month <= 12, fn ($qr) => $qr->whereMonth('start_date', $month))
                ->orderBy('start_date')
                ->paginate(12)
                ->withQueryString();
        } catch (\Throwable $e) {
            report($e);
            $events = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
        }

        return view('events.index', compact('events', 'q', 'paid', 'month'));
    }

    public function show(Event $event)
    {
        abort_unless($event->is_active, 404);

        return view('events.show', compact('event'));
    }
}
