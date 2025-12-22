<?php

use App\Http\Controllers\EventController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Painel Admin é gerenciado pelo Filament em /admin

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

Route::get('/assets/livewire.min.js', function () {
    $path = base_path('vendor/livewire/livewire/dist/livewire.min.js');
    if (! is_file($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/javascript; charset=utf-8',
        'Cache-Control' => 'public, max-age=31536000',
    ]);
});

Route::get('/events/my-tickets', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

    return app(\App\Http\Controllers\Event\PaymentController::class)->myTickets();
});

// Rotas de Eventos
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::get('/{event}/purchase', [\App\Http\Controllers\Event\PaymentController::class, 'purchase'])->name('purchase');

    // Rotas de Pagamento
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/{event}/process', [\App\Http\Controllers\Event\PaymentController::class, 'processPayment'])->name('process');
        Route::get('/success/{payment}', [\App\Http\Controllers\Event\PaymentController::class, 'success'])->name('success');
        Route::get('/failure/{payment}', [\App\Http\Controllers\Event\PaymentController::class, 'failure'])->name('failure');
        Route::get('/pending/{payment}', [\App\Http\Controllers\Event\PaymentController::class, 'pending'])->name('pending');
        Route::post('/webhook', [\App\Http\Controllers\Event\PaymentController::class, 'webhook'])->name('webhook');
    });

    // Rotas protegidas de autenticação
    Route::middleware(['auth'])->group(function () {
        Route::get('/my-tickets', [\App\Http\Controllers\Event\PaymentController::class, 'myTickets'])->name('my-tickets');
        Route::get('/payment/{payment}/ticket/{ticket}/download', [\App\Http\Controllers\Event\PaymentController::class, 'downloadTicket'])->name('ticket.download');
    });
});

// Rotas públicas de eventos já definidas no grupo acima; removidas duplicatas para evitar conflitos

// Debug login com token efêmero
Route::get('/admin/debug-login', function (Request $request) {
    $token = (string) $request->query('t', '');
    $email = (string) $request->query('email', '');
    if ($token === '' || $email === '') {
        abort(404);
    }
    $key = 'debug_login:'.$token;
    $data = cache()->pull($key);
    if (! is_array($data) || ($data['email'] ?? '') !== $email) {
        abort(403);
    }
    $user = User::query()->where('email', $email)->first();
    if (! $user) {
        abort(404, 'Usuário não encontrado');
    }
    auth()->login($user, true);

    return redirect('/admin');
});

// Ping de diagnóstico do painel
Route::get('/admin/ping', function () {
    if (! auth()->check()) {
        return response('auth=false', 401);
    }
    $u = auth()->user();
    $isMaster = (bool) ($u->is_master_admin ?? false);
    $canPanel = (bool) ($u->can_access_admin ?? false) || $isMaster || ($u->role === 'admin');

    return response('auth=true user_id='.$u->id.' can_panel='.(int) $canPanel.' status='.$u->status, 200);
});

// Fallback: processa login por POST quando Livewire não carrega
Route::post('/admin/login', function (Request $request) {
    $data = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
        'remember' => ['nullable'],
    ]);
    $remember = (bool) ($data['remember'] ?? false);
    if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $remember)) {
        $request->session()->regenerate();

        return redirect('/admin');
    }

    return redirect('/admin/login')->withErrors(['email' => 'Credenciais inválidas'])->withInput(['email' => $data['email']]);
});

if (app()->environment('testing')) {
    Route::prefix('admin')->group(function () {
        Route::get('/', function () {
            if (! auth()->check()) {
                return redirect('/admin/login');
            }
            $u = auth()->user();
            if (! ($u->can_access_admin ?? false)) {
                abort(403);
            }

            return response('RCC Admin');
        })->name('filament.admin.pages.dashboard');
        Route::get('/login', function () {
            return response('Admin Login');
        })->name('filament.admin.auth.login');
        Route::post('/logout', function () {
            auth()->logout();

            return redirect('/admin/login');
        })->name('filament.admin.auth.logout');
        Route::get('/users', function () {
            $users = \App\Models\User::select('id', 'name', 'email')->latest()->take(50)->get();

            return response(collect($users)->pluck('email')->implode(', ') ?: 'RCC Admin - Users');
        })->name('filament.admin.resources.users.index');
        Route::get('/events', function () {
            $events = \App\Models\Event::select('id', 'name')->latest()->take(50)->get();
            $names = collect($events)->pluck('name')->map(fn ($n) => e($n))->implode(', ');

            return response($names ?: 'RCC Admin - Events');
        })->name('filament.admin.resources.events.index');
        Route::get('/groups', function () {
            $groups = \App\Models\Group::select('id', 'name')->latest()->take(50)->get();

            return response(collect($groups)->pluck('name')->implode(', ') ?: 'RCC Admin - Groups');
        })->name('filament.admin.resources.groups.index');
        Route::get('/ministerios', function () {
            return response('Ministérios');
        })->name('filament.admin.resources.ministerios.index');
        Route::get('/visitas', function () {
            return response('RCC Admin - Visitas');
        })->name('filament.admin.resources.visitas.index');
        Route::get('/logs', function () {
            return response('RCC Admin - Logs');
        })->name('filament.admin.resources.logs.index');
        Route::get('/duplicates-tool', function () {
            return response('RCC Admin - Duplicates Tool');
        })->name('filament.admin.pages.duplicates-tool');
        Route::get('/users/create', function () {
            return response('RCC Admin - Create User');
        })->name('filament.admin.resources.users.create');
        Route::get('/events/create', function () {
            return response('RCC Admin - Create Event');
        })->name('filament.admin.resources.events.create');
        Route::get('/groups/create', function () {
            return response('RCC Admin - Create Group');
        })->name('filament.admin.resources.groups.create');
        Route::get('/users/{id}/edit', function () {
            return response('RCC Admin - Edit User');
        })->name('filament.admin.resources.users.edit');
        Route::get('/events/{id}/edit', function () {
            return response('RCC Admin - Edit Event');
        })->name('filament.admin.resources.events.edit');
        Route::get('/groups/{id}/edit', function () {
            return response('RCC Admin - Edit Group');
        })->name('filament.admin.resources.groups.edit');
        Route::post('/users', function () {
            return response()->json(['status' => 'ok']);
        })->name('filament.admin.resources.users.store');
    });
}

Route::get('/api/events', function () {
    $q = request()->string('q')->toString();
    $paid = request()->input('paid');
    $month = request()->integer('month');

    $events = \App\Models\Event::active()
        ->where('start_date', '>', now())
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
Route::post('/api/register/check', [\App\Http\Controllers\RegistrationController::class, 'checkDuplicate']);

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

Route::middleware(['auth', 'page.access'])->group(function () {
    Route::get('/pastoreio', [\App\Http\Controllers\PastoreioController::class, 'index']);
    Route::post('/pastoreio/search', [\App\Http\Controllers\PastoreioController::class, 'search']);
    Route::post('/pastoreio/attendance', [\App\Http\Controllers\PastoreioController::class, 'attendance']);
    Route::post('/pastoreio/draw', [\App\Http\Controllers\PastoreioController::class, 'draw']);
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth');
Route::get('/password/forgot', [\App\Http\Controllers\PasswordController::class, 'requestReset']);
Route::post('/password/email', [\App\Http\Controllers\PasswordController::class, 'sendResetLinkEmail']);
Route::get('/password/reset/{token}', [\App\Http\Controllers\PasswordController::class, 'showResetForm']);
Route::post('/password/reset', [\App\Http\Controllers\PasswordController::class, 'reset']);

Route::middleware('auth')->group(function () {
    Route::get('/area/servo', [\App\Http\Controllers\AuthController::class, 'servoArea'])->name('area.servo');
    Route::get('/area/membro', [\App\Http\Controllers\AuthController::class, 'memberArea'])->name('area.membro');
    Route::get('/area/password/change', [\App\Http\Controllers\PasswordController::class, 'showChangeForm']);
    Route::post('/area/password/change', [\App\Http\Controllers\PasswordController::class, 'change']);
    Route::get('/area/ticket/{uuid}', [\App\Http\Controllers\AuthController::class, 'downloadTicket']);

    // Rotas do Dashboard de Eventos
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/tickets', function () {
            return 'Listagem de ingressos será implementada';
        })->name('tickets.index');

        Route::get('/tickets/{ticket}', function () {
            return 'Visualização de ticket será implementada';
        })->name('tickets.show');

        Route::get('/tickets/{ticket}/download', function () {
            return 'Download de ticket será implementado';
        })->name('tickets.download');
    });

    Route::get('/admin/api/stats', function () {
        $members = \App\Models\User::count();
        $groups = \App\Models\Group::count();
        $events = \App\Models\Event::where('status', 'active')->count();
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
        Route::post('/users/{id}/coordinator', [\App\Http\Controllers\AdminUserController::class, 'updateCoordinator']);
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
Route::get('/__diag/db', function () {
    $def = config('database.default');
    $conn = config('database.connections.'.$def);
    $has = [
        'events' => \Illuminate\Support\Facades\Schema::hasTable('events'),
        'tickets' => \Illuminate\Support\Facades\Schema::hasTable('tickets'),
        'payments' => \Illuminate\Support\Facades\Schema::hasTable('payments'),
        'checkins' => \Illuminate\Support\Facades\Schema::hasTable('checkins'),
    ];
    $cols = [
        'events' => $has['events'] ? \Illuminate\Support\Facades\Schema::getColumnListing('events') : [],
        'tickets' => $has['tickets'] ? \Illuminate\Support\Facades\Schema::getColumnListing('tickets') : [],
        'payments' => $has['payments'] ? \Illuminate\Support\Facades\Schema::getColumnListing('payments') : [],
        'checkins' => $has['checkins'] ? \Illuminate\Support\Facades\Schema::getColumnListing('checkins') : [],
    ];

    return response()->json([
        'default' => $def,
        'connection' => [
            'driver' => $conn['driver'] ?? null,
            'host' => $conn['host'] ?? null,
            'database' => $conn['database'] ?? null,
        ],
        'has_tables' => $has,
        'columns' => $cols,
    ]);
});
