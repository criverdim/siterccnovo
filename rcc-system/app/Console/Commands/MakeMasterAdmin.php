<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeMasterAdmin extends Command
{
    protected $signature = 'rcc:make-master-admin {email} {--password=} {--name=} {--phone=} {--whatsapp=}';

    protected $description = 'Cria ou promove um usuário a Administrador Master para acesso ao painel /admin';

    public function handle(): int
    {
        $email = trim((string) $this->argument('email'));
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('E-mail inválido.');

            return self::FAILURE;
        }

        $password = (string) ($this->option('password') ?? '');
        $name = (string) ($this->option('name') ?? '');
        $phone = (string) ($this->option('phone') ?? '');
        $whatsapp = (string) ($this->option('whatsapp') ?? '');

        /** @var \App\Models\User $user */
        $user = User::query()->where('email', $email)->first();
        $isNew = false;
        if (! $user) {
            $isNew = true;
            $user = new User;
            $user->email = $email;
        }

        if ($name !== '') {
            $user->name = $name;
        } elseif ($isNew && $user->name === null) {
            $user->name = 'Administrador RCC';
        }

        if ($phone !== '') {
            $user->phone = $phone;
        }
        if ($whatsapp !== '') {
            $user->whatsapp = $whatsapp;
        }

        if ($password !== '') {
            $user->password = Hash::make($password);
        } elseif ($isNew && empty($user->password)) {
            $this->warn('Nenhuma senha informada — mantendo senha atual ou exigindo reset.');
        }

        $user->status = 'active';
        $user->role = 'admin';
        $user->can_access_admin = true;
        $user->is_master_admin = true;

        $user->save();

        $this->info(($isNew ? 'Usuário criado' : 'Usuário atualizado').' e promovido a Administrador Master.');
        $this->line('E-mail: '.$user->email);
        $this->line('Status: '.$user->status.' | Role: '.$user->role);
        $this->line('Flags: can_access_admin=1, is_master_admin=1');
        $this->line('Acesse: /admin/login');

        return self::SUCCESS;
    }
}
