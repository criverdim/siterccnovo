<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/events', [\App\Http\Controllers\PublicEventController::class, 'index']);
Route::get('/api/events', function () {
    $q = request()->string('q')->toString();
    $paid = request()->input('paid');
    $month = request()->integer('month');

    $events = \App\Models\Event::query()
        ->where('is_active', true)
        ->when($q, fn ($qr) => $qr->where(function ($qq) use ($q) {
            $qq->where('name', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%")
                ->orWhere('location', 'like', "%$q%");
        }))
        ->when(in_array($paid, ['paid','free'], true), fn ($qr) => $qr->where('is_paid', $paid === 'paid'))
        ->when($month && $month >= 1 && $month <= 12, fn ($qr) => $qr->whereMonth('start_date', $month))
        ->orderBy('start_date')
        ->paginate(12)
        ->withQueryString();

    return response()->json($events);
});
Route::get('/events/{event}', [\App\Http\Controllers\PublicEventController::class, 'show']);

Route::get('/groups', [\App\Http\Controllers\PublicGroupController::class, 'index']);
Route::get('/groups/{group}', [\App\Http\Controllers\PublicGroupController::class, 'show']);
Route::get('/api/groups', function () {
    $q = request()->string('q')->toString();
    $weekday = request()->string('weekday')->toString();

    $groups = \App\Models\Group::query()
        ->when($q, fn ($qr) => $qr->where(function ($qq) use ($q) {
            $qq->where('name', 'like', "%$q%")
                ->orWhere('address', 'like', "%$q%");
        }))
        ->when($weekday, fn ($qr) => $qr->where('weekday', $weekday))
        ->orderBy('name')
        ->get(['id','name','weekday','time','address','photos']);

    return response()->json($groups);
});

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/register', [\App\Http\Controllers\RegistrationController::class, 'create']);
Route::post('/register', [\App\Http\Controllers\RegistrationController::class, 'register']);
Route::post('/events/{event}/participate', [\App\Http\Controllers\EventParticipationController::class, 'participate']);
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'checkout']);
Route::post('/webhooks/mercadopago', [\App\Http\Controllers\MercadoPagoWebhookController::class, 'handle']);

Route::get('/pastoreio', [\App\Http\Controllers\PastoreioController::class, 'index']);
Route::post('/pastoreio/search', [\App\Http\Controllers\PastoreioController::class, 'search']);
Route::post('/pastoreio/attendance', [\App\Http\Controllers\PastoreioController::class, 'attendance']);
Route::post('/pastoreio/draw', [\App\Http\Controllers\PastoreioController::class, 'draw']);

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth');
Route::get('/password/forgot', [\App\Http\Controllers\PasswordController::class, 'requestReset']);
Route::post('/password/email', [\App\Http\Controllers\PasswordController::class, 'sendResetLinkEmail']);
Route::get('/password/reset/{token}', [\App\Http\Controllers\PasswordController::class, 'showResetForm']);
Route::post('/password/reset', [\App\Http\Controllers\PasswordController::class, 'reset']);

Route::middleware('auth')->group(function () {
    Route::get('/area/servo', [\App\Http\Controllers\AuthController::class, 'servoArea']);
    Route::get('/area/membro', [\App\Http\Controllers\AuthController::class, 'memberArea']);
    Route::get('/area/password/change', [\App\Http\Controllers\PasswordController::class, 'showChangeForm']);
    Route::post('/area/password/change', [\App\Http\Controllers\PasswordController::class, 'change']);
    Route::get('/area/ticket/{uuid}', [\App\Http\Controllers\AuthController::class, 'downloadTicket']);
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::get('/admin/api/stats', function () {
        $members = \App\Models\User::count();
        $groups = \App\Models\Group::count();
        $events = \App\Models\Event::where('is_active', true)->count();
        $registrationsToday = \App\Models\User::whereDate('created_at', today())->count();

        return response()->json([
            'members' => $members,
            'groups' => $groups,
            'events' => $events,
            'registrations_today' => $registrationsToday,
        ]);
    });
    Route::get('/area/api/participations', function () {
        $items = \App\Models\EventParticipation::with('event')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function($p){
                return [
                    'event' => [
                        'name' => $p->event->name ?? null,
                        'start_date' => optional($p->event->start_date)->format('d/m/Y'),
                        'start_time' => $p->event->start_time ?? null,
                    ],
                    'payment_status' => $p->payment_status,
                    'ticket_uuid' => $p->ticket_uuid,
                ];
            });
        return response()->json($items);
    });
});
// Safe landing for admin dashboard link in public layout
Route::get('/admin/dashboard', function () {
    return redirect('/admin');
});
