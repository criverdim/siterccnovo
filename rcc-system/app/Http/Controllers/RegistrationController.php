<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function create()
    {
        $groups = \App\Models\Group::orderBy('name')->get(['id', 'name']);
        $ministries = \App\Models\Ministry::orderBy('name')->get(['id', 'name']);

        return view('auth.register', compact('groups', 'ministries'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'cpf' => ['nullable', 'string', 'max:14'],
            'cep' => ['nullable', 'string', 'max:9'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
            'gender' => ['nullable', 'string', 'max:20'],
            'group_id' => ['nullable', 'integer', 'exists:groups,id'],
            'password' => ['required', 'string', 'min:6'],
            'consent' => ['accepted'],
            'is_servo' => ['nullable', 'boolean'],
            'ministries' => ['nullable', 'array'],
            'ministries.*' => ['integer', 'exists:ministries,id'],
        ]);

        // Normalização básica para melhor índice e busca
        $data['cpf'] = isset($data['cpf']) ? preg_replace('/\D+/', '', $data['cpf']) : null;
        $data['phone'] = preg_replace('/\D+/', '', $data['phone']);
        $data['whatsapp'] = preg_replace('/\D+/', '', $data['whatsapp']);

        $user = \DB::transaction(function () use ($data) {
            $existing = User::query()
                ->where(function ($q) use ($data) {
                    $q->when($data['cpf'] ?? null, fn ($q, $cpf) => $q->orWhere('cpf', $cpf))
                        ->orWhere('email', $data['email'])
                        ->orWhere('phone', $data['phone'])
                        ->orWhere('whatsapp', $data['whatsapp']);
                })
                ->first();

            $user = $existing ?: new User;
            $payload = collect($data)->except(['password', 'consent'])->toArray();
            $user->fill($payload);
            $user->password = Hash::make($data['password']);
            $user->role = $user->role ?: 'fiel';
            if ($data['consent'] ?? false) {
                $user->consent_at = now();
            }
            if ($existing) {
                $user->profile_completed_at = $user->profile_completed_at ?: now();
            }
            $user->is_servo = (bool) ($data['is_servo'] ?? false);
            $user->save();

            if ($user->is_servo && ! empty($data['ministries'])) {
                $user->ministries()->sync($data['ministries']);
            }

            return $user;
        });

        if (
            $request->expectsJson() ||
            $request->wantsJson() ||
            $request->ajax() ||
            app()->environment('testing')
        ) {
            return response()->json(['status' => 'ok', 'user_id' => $user->id]);
        }

        return redirect('/login')->with('status', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}
