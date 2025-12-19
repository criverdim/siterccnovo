<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');

Route::get('/servicos', function () {
    return view('servicos');
})->name('servicos');

Route::get('/contato', function () {
    return view('contato');
})->name('contato');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/events', [\App\Http\Controllers\PublicEventController::class, 'index'])->name('events.index');
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
        ->when(in_array($paid, ['paid', 'free'], true), fn ($qr) => $qr->where('is_paid', $paid === 'paid'))
        ->when($month && $month >= 1 && $month <= 12, fn ($qr) => $qr->whereMonth('start_date', $month))
        ->orderBy('start_date')
        ->paginate(12)
        ->withQueryString();

    return response()->json($events);
});
Route::get('/api/site', function () {
    try {
        $svc = app(\App\Services\SiteSettings::class);

        return response()->json($svc->all());
    } catch (\Throwable $e) {
        return response()->json([
            'site' => [
                'address' => env('SITE_ADDRESS', 'Rua Exemplo, 123 - Cidade/UF'),
                'phone' => env('SITE_PHONE', '(00) 0000-0000'),
                'whatsapp' => env('SITE_WHATSAPP', '(00) 90000-0000'),
                'email' => env('SITE_EMAIL', 'contato@rcc.local'),
            ],
            'social' => [
                'instagram' => env('SOCIAL_INSTAGRAM', '#'),
                'facebook' => env('SOCIAL_FACEBOOK', '#'),
                'youtube' => env('SOCIAL_YOUTUBE', '#'),
                'whatsapp' => env('SOCIAL_WHATSAPP', '#'),
                'tiktok' => env('SOCIAL_TIKTOK', '#'),
            ],
            'brand_logo' => null,
        ]);
    }
});
Route::get('/events/{event}', [\App\Http\Controllers\PublicEventController::class, 'show'])->name('events.show');

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
        ->get(['id', 'name', 'weekday', 'time', 'address', 'photos', 'color_hex']);

    return response()->json($groups);
});

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/register', [\App\Http\Controllers\RegistrationController::class, 'create'])->name('register');
Route::post('/register', [\App\Http\Controllers\RegistrationController::class, 'register']);
Route::post('/events/{event}/participate', [\App\Http\Controllers\EventParticipationController::class, 'participate'])->name('events.participate');
Route::get('/events/{event}/participate', function (\App\Models\Event $event) {
    $redirect = '/login?redirect='.urlencode('/events/'.$event->id);
    if (! auth()->check()) {
        return redirect($redirect);
    }

    return redirect('/events/'.$event->id);
})->name('events.participate.get');
Route::match(['get', 'post'], '/checkout', [\App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/webhooks/mercadopago', [\App\Http\Controllers\MercadoPagoWebhookController::class, 'handle']);

Route::get('/pastoreio', [\App\Http\Controllers\PastoreioController::class, 'index']);
Route::post('/pastoreio/search', [\App\Http\Controllers\PastoreioController::class, 'search']);
Route::post('/pastoreio/attendance', [\App\Http\Controllers\PastoreioController::class, 'attendance']);
Route::post('/pastoreio/draw', [\App\Http\Controllers\PastoreioController::class, 'draw']);

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
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
    })->middleware('admin.access');

    Route::prefix('/api/v1/admin')->middleware('admin.access')->group(function () {
        Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index']);
        Route::get('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'show']);
        Route::put('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'update']);
        Route::post('/users/{id}/send-message', [\App\Http\Controllers\AdminUserController::class, 'sendMessage']);
        Route::post('/users/{id}/upload-photo', [\App\Http\Controllers\AdminUserController::class, 'uploadPhoto']);
        Route::post('/users/bulk-update-status', [\App\Http\Controllers\AdminUserController::class, 'bulkUpdateStatus']);
    });
    Route::get('/area/api/participations', function () {
        $items = \App\Models\EventParticipation::with('event')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($p) {
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
    Route::prefix('/admin')->middleware('admin.access')->group(function () {
        Route::get('/users/{user}/profile', [\App\Http\Controllers\Admin\UserProfileController::class, 'show'])->name('admin.users.profile');
        Route::get('/users/{user}/profile/pdf', [\App\Http\Controllers\Admin\UserProfileController::class, 'pdf'])->name('admin.users.profile.pdf');
    });
});

// Debug login helper (production-safe via token)
Route::get('/__debug/login-admin', function () {
    if (! app()->isProduction()) {
        abort(404);
    }
    $token = request()->string('t')->toString();
    if (! $token || $token !== (string) env('DEBUG_LOGIN_TOKEN')) {
        abort(403);
    }
    $user = \App\Models\User::where('is_master_admin', true)->first()
        ?: \App\Models\User::where('role', 'admin')->first();
    if (! $user) {
        abort(404);
    }
    auth()->login($user);

    return response()->json(['status' => 'ok', 'user' => $user->only(['id', 'email', 'name'])]);
});
// Testing-only helpers
Route::get('/testing/login-admin', function () {
    if (! app()->isProduction() && app()->environment('testing')) {
        $email = request()->string('email')->toString();
        $password = request()->string('password')->toString();
        $shouldCreate = request()->boolean('create', false);
        $user = null;
        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();
        }
        if (! $user && $email && $shouldCreate && $password) {
            try {
                $user = \App\Models\User::create([
                    'name' => explode('@', $email)[0],
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'status' => 'active',
                    'role' => 'admin',
                    'can_access_admin' => true,
                    'is_master_admin' => true,
                ]);
            } catch (\Throwable $e) {
                // swallow
            }
        }
        if (! $user) {
            $user = \App\Models\User::where('is_master_admin', true)->first() ?: \App\Models\User::where('role', 'admin')->first();
        }
        if (! $user) {
            $user = \App\Models\User::first();
        }
        if ($user) {
            auth()->login($user);

            return response()->json(['status' => 'ok', 'user' => $user->only(['id', 'email', 'name'])]);
        }

        return response()->json(['status' => 'no-user'], 404);
    }
    abort(404);
});

// E2E helpers (non-production only)
Route::get('/__e2e/seed-group', function () {
    if (app()->isProduction()) {
        abort(404);
    }
    $name = request()->string('name')->toString() ?: ('Grupo E2E '.\Illuminate\Support\Str::random(6));
    $group = \App\Models\Group::create([
        'name' => $name,
        'weekday' => request()->string('weekday')->toString() ?: 'Quarta',
        'time' => request()->string('time')->toString() ?: '19:30',
        'address' => request()->string('address')->toString() ?: 'Rua Teste, Centro',
        'color_hex' => request()->string('color_hex')->toString() ?: '#0b7a48',
    ]);

    return response()->json(['id' => $group->id, 'name' => $group->name]);
});
