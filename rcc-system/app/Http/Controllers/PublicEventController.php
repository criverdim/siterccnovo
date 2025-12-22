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
        $category = request()->string('category')->toString();
        $location = request()->string('location')->toString();
        $dateFrom = request()->string('date_from')->toString();
        $dateTo = request()->string('date_to')->toString();

        try {
            $baseQuery = Event::query()
                ->where('is_active', true)
                ->when($q, fn ($qr) => $qr->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%$q%")
                        ->orWhere('description', 'like', "%$q%")
                        ->orWhere('location', 'like', "%$q%");
                }))
                ->when(in_array($paid, ['paid', 'free'], true), fn ($qr) => $qr->where('is_paid', $paid === 'paid'))
                ->when($month && $month >= 1 && $month <= 12, fn ($qr) => $qr->whereMonth('start_date', $month))
                ->when($category, fn ($qr) => $qr->where('category', $category))
                ->when($location, fn ($qr) => $qr->where('location', 'like', "%$location%"))
                ->when($dateFrom, fn ($qr) => $qr->whereDate('start_date', '>=', $dateFrom))
                ->when($dateTo, fn ($qr) => $qr->whereDate('end_date', '<=', $dateTo));

            $events = $baseQuery
                ->orderBy('start_date')
                ->paginate(12)
                ->withQueryString();
        } catch (\Throwable $e) {
            report($e);
            $events = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
        }

        if (request()->wantsJson()) {
            $collection = $events->getCollection()->map(function (Event $ev) {
                $photo = $ev->featured_image
                    ?? ($ev->folder_image ?: null)
                    ?? (is_array($ev->gallery_images ?? null) && count($ev->gallery_images) ? $ev->gallery_images[0] : null);
                $url = $photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($photo) : asset('favicon.ico');

                return [
                    'id' => $ev->id,
                    'name' => $ev->name,
                    'category' => $ev->category,
                    'location' => $ev->location,
                    'is_paid' => (bool) $ev->is_paid,
                    'price' => $ev->price,
                    'start_date' => optional($ev->start_date)->format('Y-m-d'),
                    'description' => \Illuminate\Support\Str::limit(strip_tags($ev->description ?? ''), 180),
                    'photo_thumb_url' => $url,
                    'show_url' => route('events.show', $ev),
                    'participate_url' => route('events.participate.get', $ev),
                ];
            })->values();

            return response()->json([
                'events' => $collection,
                'nextPageUrl' => $events->nextPageUrl(),
            ]);
        }

        $categories = Event::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values();

        return view('events.index', [
            'events' => $events,
            'q' => $q,
            'paid' => $paid,
            'month' => $month,
            'category' => $category,
            'categories' => $categories,
            'location' => $location,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function show(Event $event)
    {
        abort_unless($event->is_active, 404);

        return view('events.show', compact('event'));
    }
}
