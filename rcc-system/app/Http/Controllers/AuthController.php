<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EventParticipation;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $area = $request->query('area');
        return view('auth.login', compact('area'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'area' => ['required', 'in:servo,membro'],
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

        if ($data['area'] === 'servo' && ! $user->is_servo) {
            Auth::logout();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Acesso restrito a servos'], 403);
            }
            return back()->withErrors(['area' => 'Acesso restrito a servos'])->withInput();
        }
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'ok', 'redirect' => ($data['area'] === 'servo' ? '/area/servo' : '/area/membro')]);
        }
        return redirect($data['area'] === 'servo' ? '/area/servo' : '/area/membro');
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
        ]);

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return response()->json(['status' => 'error'], 401);
        }

        return response()->json(['status' => 'ok']);
    }

    public function downloadTicket(string $uuid)
    {
        $participation = EventParticipation::with('event')
            ->where('ticket_uuid', $uuid)
            ->firstOrFail();

        abort_unless($participation->user_id === Auth::id(), 403);

        $path = Storage::disk('local')->path('tickets/ticket_' . $uuid . '.pdf');

        abort_unless(file_exists($path), 404);

        $filename = 'ingresso-' . ($participation->event?->name ?? 'evento') . '.pdf';

        return response()->download($path, $filename);
    }
}
