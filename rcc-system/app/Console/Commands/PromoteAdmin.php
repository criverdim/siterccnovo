<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class PromoteAdmin extends Command
{
    protected $signature = 'user:promote-admin {email} {--password=}';

    protected $description = 'Promove um usuário a administrador (define role, flags e senha opcional)';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) ($this->option('password') ?? '');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Informe um e-mail válido');

            return static::FAILURE;
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            $user = new User;
            $user->email = $email;
            $user->name = 'Administrador';
            $user->status = 'active';
            $user->password = Hash::make($password ?: bin2hex(random_bytes(8)));
        } elseif ($password) {
            $user->password = Hash::make($password);
        }

        $user->role = 'admin';
        $user->can_access_admin = true;
        $user->is_master_admin = true;
        $user->status = 'active';
        $user->save();

        $this->info('Usuário promovido: '.$user->email);

        return static::SUCCESS;
    }
}
