<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestAuth extends Command
{
    protected $signature = 'rcc:test-auth {email} {password}';

    protected $description = 'Testa autenticação do guard web com e-mail e senha informados';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) $this->argument('password');

        /** @var \App\Models\User|null $user */
        $user = User::query()->where('email', $email)->first();
        if (! $user) {
            $this->error('Usuário não encontrado: '.$email);

            return self::FAILURE;
        }

        $this->line('User ID: '.$user->id.' | status='.$user->status.' | role='.$user->role);
        $this->line('Flags: can_access_admin='.(int) ($user->can_access_admin ?? false).', is_master_admin='.(int) ($user->is_master_admin ?? false));

        $hashOk = Hash::check($password, $user->password);
        $this->line('Hash::check => '.($hashOk ? 'OK' : 'FALHA'));

        $attempt = Auth::guard('web')->attempt(['email' => $email, 'password' => $password]);
        $this->line('Auth::attempt(web) => '.($attempt ? 'SUCESSO' : 'FALHA'));

        return self::SUCCESS;
    }
}
