<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProbeAdminLogin extends Command
{
    protected $signature = 'rcc:probe-admin-login {email} {password} {--base-url=} {--dump=/tmp/probe-admin-login.json}';

    protected $description = 'Simula login no painel admin e coleta logs e respostas para diagnóstico';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) $this->argument('password');
        $base = rtrim((string) ($this->option('base-url') ?: config('app.url')), '/');
        $dump = (string) $this->option('dump');

        $jar = new \GuzzleHttp\Cookie\CookieJar;
        $timeline = [];
        $now = now()->format('Y-m-d H:i:s');

        $timeline[] = ['step' => 'start', 'time' => $now, 'base' => $base];

        // 1) GET /admin/login
        $respLoginGet = Http::withOptions(['cookies' => $jar, 'allow_redirects' => true])
            ->get($base.'/admin/login');
        $htmlLogin = $respLoginGet->body();
        $csrf = $this->extractCsrfToken($htmlLogin);
        $timeline[] = [
            'step' => 'get_login',
            'status' => $respLoginGet->status(),
            'len' => strlen($htmlLogin),
            'csrf' => $csrf,
            'set_cookies' => $jar->toArray(),
        ];

        // 2) POST /admin/login
        $respLoginPost = Http::withOptions(['cookies' => $jar, 'allow_redirects' => false])
            ->withHeaders([
                'X-CSRF-TOKEN' => $csrf,
                'Accept' => 'text/html,application/xhtml+xml',
            ])
            ->asForm()
            ->post($base.'/admin/login', [
                'email' => $email,
                'password' => $password,
                'remember' => 'on',
            ]);
        $timeline[] = [
            'step' => 'post_login',
            'status' => $respLoginPost->status(),
            'location' => $respLoginPost->header('Location'),
            'cookies' => $jar->toArray(),
        ];

        // 3) GET redirect (esperado /admin)
        $location = $respLoginPost->header('Location') ?: $base.'/admin';
        if (! Str::startsWith((string) $location, 'http')) {
            $location = $base.(Str::startsWith($location, '/') ? $location : '/'.$location);
        }
        $respAdmin = Http::withOptions(['cookies' => $jar, 'allow_redirects' => true])
            ->get($location);
        $timeline[] = [
            'step' => 'get_admin',
            'status' => $respAdmin->status(),
            'len' => strlen((string) $respAdmin->body()),
        ];

        // 4) GET /admin/ping
        $respPing = Http::withOptions(['cookies' => $jar])->get($base.'/admin/ping');
        $timeline[] = [
            'step' => 'get_ping',
            'status' => $respPing->status(),
            'body' => $respPing->body(),
        ];

        // 5) HEAD/POST Livewire update
        $respLwHead = Http::withOptions(['cookies' => $jar])->withHeaders([
            'X-CSRF-TOKEN' => $csrf ?: ($respLoginGet->header('X-CSRF-TOKEN')[0] ?? ''),
        ])->post($base.'/admin/livewire/update', [])->status();
        $timeline[] = [
            'step' => 'post_livewire_update',
            'status' => $respLwHead,
        ];

        // 6) Coleta logs recentes
        $recentLogs = $this->tailLog(200);
        $timeline[] = ['step' => 'tail_logs', 'lines' => $recentLogs];

        // Dump
        try {
            file_put_contents($dump, json_encode(['timeline' => $timeline], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info('Dump salvo em: '.$dump);
        } catch (\Throwable $e) {
            $this->warn('Falha ao salvar dump: '.$e->getMessage());
        }

        // Saída amigável
        foreach ($timeline as $t) {
            $this->line(json_encode($t, JSON_UNESCAPED_UNICODE));
        }

        return self::SUCCESS;
    }

    protected function extractCsrfToken(string $html): string
    {
        if (preg_match('/<meta\\s+name=\"csrf-token\"\\s+content=\"([^\"]+)\"/i', $html, $m)) {
            return (string) $m[1];
        }

        return '';
    }

    protected function tailLog(int $lines = 200): array
    {
        $path = storage_path('logs/laravel.log');
        if (! file_exists($path)) {
            return [];
        }
        try {
            $f = new \SplFileObject($path, 'r');
            $f->seek(PHP_INT_MAX);
            $last = $f->key();
            $start = max($last - $lines, 0);
            $out = [];
            for ($i = $start; $i <= $last; $i++) {
                $f->seek($i);
                $line = $f->current();
                if ($line !== false) {
                    $out[] = rtrim($line, "\r\n");
                }
            }

            return $out;
        } catch (\Throwable $e) {
            return [];
        }
    }
}
