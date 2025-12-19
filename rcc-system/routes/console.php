<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('wa:test {--user=} {--phone=} {--message=}', function () {
    $userId = $this->option('user');
    $phoneOpt = $this->option('phone');
    $text = $this->option('message') ?: 'Mensagem de teste do RCC System';

    $user = null;
    if ($userId) {
        $user = \App\Models\User::find($userId);
    }
    if (! $user) {
        $user = \App\Models\User::where('is_master_admin', true)->first() ?: \App\Models\User::where('role', 'admin')->first() ?: \App\Models\User::first();
    }
    if (! $user && ! $phoneOpt) {
        $this->error('Nenhum usuário encontrado e nenhum telefone informado.');

        return 1;
    }

    $wa = \App\Models\WaMessage::create([
        'user_id' => $user?->id,
        'message' => $text,
        'payload' => ['cli' => true],
        'status' => 'pending',
    ]);

    if ($phoneOpt) {
        $userObj = $user ?: new \App\Models\User;
        $userObj->whatsapp = $phoneOpt;
        $wa->setRelation('user', $userObj);
    } else {
        $wa->load('user');
    }

    $svc = app(\App\Services\WhatsAppService::class);
    $result = $svc->send($wa);
    $out = [
        'status' => $result,
        'wa_message_id' => $wa->id,
        'payload' => $wa->payload,
    ];
    $this->info(json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    return 0;
})->purpose('Envia uma mensagem de teste via WhatsApp');

Artisan::command('user:promote-admin {email} {--password=}', function (string $email) {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('E-mail inválido');

        return 1;
    }
    $pwd = (string) ($this->option('password') ?? '');
    $u = \App\Models\User::where('email', $email)->first();
    if (! $u) {
        $u = new \App\Models\User;
        $u->email = $email;
        $u->name = 'Administrador';
        $u->status = 'active';
        $u->password = \Illuminate\Support\Facades\Hash::make($pwd ?: bin2hex(random_bytes(8)));
    } elseif ($pwd) {
        $u->password = \Illuminate\Support\Facades\Hash::make($pwd);
    }
    $u->role = 'admin';
    $u->can_access_admin = true;
    $u->is_master_admin = true;
    $u->status = 'active';
    $u->save();
    $this->info('Usuário promovido: '.$u->email);

    return 0;
})->purpose('Promove um usuário para administrador');
