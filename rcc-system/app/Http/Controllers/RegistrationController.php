<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    private function digits(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value);
    }

    private function normalizeName(?string $value): string
    {
        return \Illuminate\Support\Str::of((string) $value)
            ->lower()
            ->ascii()
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();
    }

    private function findDuplicateUser(array $data): array
    {
        $email = isset($data['email']) ? trim((string) $data['email']) : '';
        $cpf = $this->digits($data['cpf'] ?? null);
        $phone = $this->digits($data['phone'] ?? null);
        $whatsapp = $this->digits($data['whatsapp'] ?? null);
        $name = isset($data['name']) ? trim((string) $data['name']) : '';
        $birth = $data['birth_date'] ?? null;

        $reasons = [];
        $possibleReasons = [];
        $user = null;

        if ($email || $cpf || $phone || $whatsapp) {
            $user = User::query()
                ->where(function ($q) use ($email, $cpf, $phone, $whatsapp) {
                    if ($email) {
                        $q->orWhere('email', $email);
                    }
                    if ($cpf) {
                        $q->orWhere('cpf', $cpf);
                    }
                    if ($phone) {
                        $q->orWhere('phone', $phone);
                    }
                    if ($whatsapp) {
                        $q->orWhere('whatsapp', $whatsapp);
                    }
                })
                ->first();

            if ($user) {
                if ($email && $user->email === $email) {
                    $reasons[] = 'email';
                }
                if ($cpf && $user->cpf === $cpf) {
                    $reasons[] = 'cpf';
                }
                if ($phone && $user->phone === $phone) {
                    $reasons[] = 'phone';
                }
                if ($whatsapp && $user->whatsapp === $whatsapp) {
                    $reasons[] = 'whatsapp';
                }
            }
        }

        if (! $user && $name && $birth) {
            $normalizedName = $this->normalizeName($name);
            $candidates = User::query()
                ->whereDate('birth_date', $birth)
                ->limit(30)
                ->get(['id', 'name', 'birth_date', 'status', 'email']);

            foreach ($candidates as $candidate) {
                $score = levenshtein($this->normalizeName($candidate->name), $normalizedName);
                if ($score <= 2) {
                    $user = $candidate;
                    $reasons[] = 'name+birth_date';
                    break;
                }
                if ($score <= 5) {
                    $possibleReasons[] = 'similar_name+birth_date';
                }
            }
        }

        return [$user, $reasons, $possibleReasons];
    }

    private function logDuplicateAttempt(User $user, Request $request, array $attempted, array $reasons, string $source): void
    {
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'duplicate_registration_attempt',
            'details' => [
                'matched' => $reasons,
                'source' => $source,
                'attempted' => [
                    'email' => isset($attempted['email']) ? (string) $attempted['email'] : null,
                    'cpf' => isset($attempted['cpf']) ? (string) $attempted['cpf'] : null,
                    'phone' => isset($attempted['phone']) ? (string) $attempted['phone'] : null,
                    'whatsapp' => isset($attempted['whatsapp']) ? (string) $attempted['whatsapp'] : null,
                    'name' => isset($attempted['name']) ? (string) $attempted['name'] : null,
                    'birth_date' => isset($attempted['birth_date']) ? (string) $attempted['birth_date'] : null,
                ],
                'user_agent' => (string) $request->header('User-Agent'),
            ],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);
    }

    public function create()
    {
        $groups = \App\Models\Group::orderBy('name')->get(['id', 'name']);
        $ministries = \App\Models\Ministry::orderBy('name')->get(['id', 'name']);

        return view('auth.register', compact('groups', 'ministries'));
    }

    public function checkDuplicate(Request $request)
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:14'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
        ]);

        [$user, $reasons, $possibleReasons] = $this->findDuplicateUser($data);

        if ($user && count($reasons)) {
            $this->logDuplicateAttempt($user, $request, $data, $reasons, 'realtime');
        }

        return response()->json([
            'duplicate' => (bool) ($user && count($reasons)),
            'possible_duplicate' => (bool) (count($possibleReasons) && ! count($reasons)),
            'reasons' => $reasons ?: $possibleReasons,
            'status' => $user?->status,
            'message' => $user && count($reasons)
                ? 'Já existe um cadastro com estes dados. Faça login ou recupere sua senha.'
                : (count($possibleReasons) ? 'Encontramos dados parecidos. Verifique se você já possui cadastro.' : null),
            'links' => [
                'login' => url('/login'),
                'password_forgot' => url('/password/forgot'),
            ],
        ]);
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
            'gender' => ['required', 'in:male,female'],
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
            'gender.required' => 'Selecione o gênero',
            'gender.in' => 'Selecione Masculino ou Feminino',
        ]);

        $data['email'] = trim((string) $data['email']);
        $data['cpf'] = isset($data['cpf']) ? preg_replace('/\D+/', '', $data['cpf']) : null;
        $data['phone'] = preg_replace('/\D+/', '', $data['phone']);
        $data['whatsapp'] = preg_replace('/\D+/', '', $data['whatsapp']);

        [$existing, $reasons] = $this->findDuplicateUser($data);
        if ($existing && count($reasons)) {
            $this->logDuplicateAttempt($existing, $request, $data, $reasons, 'register');
            throw ValidationException::withMessages([
                'email' => 'Já existe um cadastro com estes dados. Faça login ou recupere sua senha.',
            ]);
        }

        $user = \DB::transaction(function () use ($data) {
            $user = new User;
            $primaryGroupId = (array) ($data['groups'] ?? []);
            $primaryGroupId = count($primaryGroupId) ? (int) $primaryGroupId[0] : null;
            $payload = collect($data)->except(['password', 'consent', 'groups'])->toArray();
            $user->fill($payload);
            $user->group_id = $primaryGroupId;
            $user->password = Hash::make($data['password']);
            $user->role = 'fiel';
            if ($data['consent'] ?? false) {
                $user->consent_at = now();
            }
            $user->profile_completed_at = now();
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
