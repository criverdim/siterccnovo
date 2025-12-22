<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IssueDebugLogin extends Command
{
    protected $signature = 'rcc:issue-debug-login {email} {--ttl=300}';

    protected $description = 'Emite um token efêmero de debug-login para o e-mail informado';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $ttl = (int) $this->option('ttl');
        $token = bin2hex(random_bytes(16));
        cache()->put('debug_login:'.$token, ['email' => $email, 'issued_at' => time()], $ttl);

        $base = rtrim(config('app.url'), '/');
        $url = $base.'/admin/debug-login?t='.$token.'&email='.urlencode($email);
        $this->info('URL de debug-login (expira em '.$ttl.'s):');
        $this->line($url);

        return self::SUCCESS;
    }
}
