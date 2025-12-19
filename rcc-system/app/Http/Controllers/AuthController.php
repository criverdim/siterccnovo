<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $area = $request->query('area');
        $redir = (string) $request->query('redirect', '');
        if (auth()->check()) {
            if (($area === 'admin') || ($redir && str_starts_with($redir, '/admin'))) {
                return redirect('/admin');
            }
        }
        if ($redir === '/admin/dashboard') {
            $redir = '/admin';
        }

        return view('auth.login', compact('area'));
    }

    public function login(Request $request)
    {
        Log::info('login.start', ['email' => $request->input('email')]);
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'area' => ['nullable', 'in:servo,membro,admin'],
            'redirect' => ['nullable', 'string'],
        ]);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Credenciais inválidas'], 422);
            }

            return back()->withErrors(['email' => 'Credenciais inválidas'])->withInput();
        }

        $user = auth()->user();
        if ($user->status !== 'active') {
            Auth::logout();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Usuário inativo ou bloqueado'], 403);
            }

            return back()->withErrors(['email' => 'Usuário inativo ou bloqueado'])->withInput();
        }

        // Se não foi enviada área, decidir conforme perfil do usuário
        $area = $data['area'] ?? null;
        $redirect = $data['redirect'] ?? null;
        if ($redirect === '/admin/dashboard') {
            $redirect = '/admin';
        }
        if ($area === null) {
            $isAdmin = (bool) ($user->is_master_admin ?? false) || ($user->role === 'admin') || ($user->can_access_admin ?? false);
            if ($isAdmin) {
                $area = 'admin';
            } elseif ($user->is_servo) {
                $area = 'servo';
            } else {
                $area = 'membro';
            }
        }

        // Validar acesso a admin
        if ($area === 'admin') {
            $isAdmin = (bool) ($user->is_master_admin ?? false) || ($user->role === 'admin') || ($user->can_access_admin ?? false);
            if (! $isAdmin) {
                Auth::logout();
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Acesso ao painel admin não autorizado'], 403);
                }

                return back()->withErrors(['area' => 'Acesso ao painel admin não autorizado'])->withInput();
            }

            $redirect = $data['redirect'] ?? null;
            $dest = '/admin';
            if ($redirect && str_starts_with($redirect, '/admin')) {
                $dest = $redirect;
            }
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'ok', 'redirect' => $dest]);
            }

            return redirect($dest);
        }

        if ($area === 'servo' && ! $user->is_servo) {
            Auth::logout();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Acesso restrito a servos'], 403);
            }

            return back()->withErrors(['area' => 'Acesso restrito a servos'])->withInput();
        }
        $dest = $area === 'servo' ? '/area/servo' : '/area/membro';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'ok', 'redirect' => $dest]);
        }

        return redirect($dest);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    public function servoArea()
    {
        $user = auth()->user();
        abort_unless($user && $user->is_servo, 403);

        return view('area.servo');
    }

    public function memberArea()
    {
        $participations = EventParticipation::with('event')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('area.membro', compact('participations'));
    }

    public function loginApi(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'area' => ['nullable', 'in:servo,membro,admin'],
        ]);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return response()->json(['status' => 'error', 'message' => 'Credenciais inválidas'], 401);
        }

        $user = auth()->user();

        // Validar se usuário está ativo
        if ($user->status !== 'active') {
            Auth::logout();

            return response()->json(['status' => 'error', 'message' => 'Usuário inativo'], 403);
        }

        $area = $data['area'] ?? null;

        // Se área é admin, validar permissões
        if ($area === 'admin') {
            $isAdmin = (bool) ($user->is_master_admin ?? false) || ($user->role === 'admin') || ($user->can_access_admin ?? false);
            if (! $isAdmin) {
                Auth::logout();

                return response()->json(['status' => 'error', 'message' => 'Acesso ao painel admin não autorizado'], 403);
            }

            return response()->json(['status' => 'ok', 'redirect' => '/admin']);
        }

        // Se área é servo
        if ($area === 'servo') {
            if (! $user->is_servo) {
                Auth::logout();

                return response()->json(['status' => 'error', 'message' => 'Acesso restrito a servos'], 403);
            }

            return response()->json(['status' => 'ok', 'redirect' => '/area/servo']);
        }

        // Se área é membro
        if ($area === 'membro') {
            return response()->json(['status' => 'ok', 'redirect' => '/area/membro']);
        }

        // Se não especificou área, verifica se é admin
        $isAdmin = (bool) ($user->is_master_admin ?? false) || ($user->role === 'admin') || ($user->can_access_admin ?? false);
        if ($isAdmin) {
            return response()->json(['status' => 'ok', 'redirect' => '/admin']);
        }

        // Caso contrário, vai para area/membro como padrão
        return response()->json(['status' => 'ok', 'redirect' => '/area/membro']);
    }

    public function downloadTicket(string $uuid)
    {
        $participation = EventParticipation::with('event')
            ->where('ticket_uuid', $uuid)
            ->firstOrFail();

        abort_unless($participation->user_id === Auth::id(), 403);

        $path = Storage::disk('local')->path('tickets/ticket_'.$uuid.'.pdf');

        abort_unless(file_exists($path), 404);

        $filename = 'ingresso-'.($participation->event?->name ?? 'evento').'.pdf';

        return response()->download($path, $filename);
    }
}
