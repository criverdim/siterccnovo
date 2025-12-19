<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPhoto;
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
        $request->merge([
            'is_servo' => $request->boolean('is_servo'),
            'consent' => $request->boolean('consent'),
            'groups' => array_map('intval', (array) $request->input('groups', [])),
            'ministries' => array_map('intval', (array) $request->input('ministries', [])),
        ]);
        $hasGroups = \App\Models\Group::query()->exists();
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
            'groups' => $hasGroups ? ['required', 'array', 'min:1'] : ['nullable', 'array'],
            'groups.*' => ['integer', 'exists:groups,id'],
            'password' => ['required', 'string', 'min:6'],
            'consent' => ['accepted'],
            'is_servo' => ['nullable', 'boolean'],
            'ministries' => ['nullable', 'array'],
            'ministries.*' => ['integer', 'exists:ministries,id'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ], [
            'name.required' => 'Informe seu nome completo',
            'email.required' => 'Informe um e-mail',
            'email.email' => 'Informe um e-mail válido',
            'phone.required' => 'Informe seu telefone',
            'whatsapp.required' => 'Informe seu WhatsApp',
            'groups.required' => 'Selecione pelo menos um grupo de oração',
            'groups.array' => 'Seleção de grupos inválida',
            'groups.*.integer' => 'Grupo inválido',
            'groups.*.exists' => 'Grupo não encontrado',
            'password.required' => 'Informe a senha',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'consent.accepted' => 'É necessário concordar com o uso dos dados conforme a LGPD',
            'is_servo.boolean' => 'Campo "Sou servo" inválido',
            'ministries.array' => 'Seleção de ministérios inválida',
            'ministries.*.integer' => 'Ministério inválido',
            'ministries.*.exists' => 'Ministério não encontrado',
            'photo.image' => 'Envie uma imagem válida',
            'photo.mimes' => 'Formatos permitidos: jpeg, png, jpg',
            'photo.max' => 'A imagem deve ter no máximo 5MB',
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
            $primaryGroupId = (array) ($data['groups'] ?? []);
            $primaryGroupId = count($primaryGroupId) ? (int) $primaryGroupId[0] : null;
            $payload = collect($data)->except(['password', 'consent', 'groups'])->toArray();
            $user->fill($payload);
            $user->group_id = $user->group_id ?: $primaryGroupId;
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

            if (! empty($data['groups'])) {
                $user->groups()->sync($data['groups']);
            }

            return $user;
        });

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('user-photos/'.$user->id, 'public');
            $user->photos()->update(['is_active' => false]);
            UserPhoto::create([
                'user_id' => $user->id,
                'file_path' => $path,
                'file_name' => $request->file('photo')->getClientOriginalName(),
                'file_size' => $request->file('photo')->getSize(),
                'mime_type' => $request->file('photo')->getMimeType(),
                'is_active' => true,
            ]);
        }

        if (
            $request->expectsJson() ||
            $request->wantsJson() ||
            $request->ajax() ||
            (app()->environment('testing') && ! env('PLAYWRIGHT'))
        ) {
            return response()->json(['status' => 'ok', 'user_id' => $user->id]);
        }

        return redirect('/login')->with('status', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}
