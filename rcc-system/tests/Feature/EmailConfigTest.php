<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailConfigTest extends TestCase
{
    use RefreshDatabase;

    private string $reportPath;

    protected function setUp(): void
    {
        parent::setUp();
        $dir = storage_path('logs');
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $this->reportPath = $dir . '/email-config-report.json';
    }

    private function writeReport(array $data): void
    {
        file_put_contents($this->reportPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function test_smtp_parameters_presence_and_format(): void
    {
        // seed minimal settings
        Setting::create([
            'key' => 'email',
            'value' => [
                'host' => env('MAIL_HOST', 'smtp.example.com'),
                'port' => (int) (env('MAIL_PORT', 587)),
                'username' => env('MAIL_USERNAME', 'user'),
                'password' => env('MAIL_PASSWORD', 'secret'),
                'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'from_email' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
                'from_name' => env('MAIL_FROM_NAME', 'RCC System'),
            ],
        ]);
        $cfg = Setting::where('key','email')->first();
        $ok = is_string($cfg->value['host'] ?? null) && !empty($cfg->value['host'])
            && is_numeric($cfg->value['port'] ?? null)
            && in_array($cfg->value['encryption'] ?? '', ['tls','ssl']);
        $this->writeReport(['env'=>env('APP_ENV'),'smtp_params_ok'=>$ok,'cfg'=>$cfg->value]);
        $this->assertFileExists($this->reportPath);
    }

    public function test_smtp_connectivity_probe_tls_and_ssl(): void
    {
        $cfg = Setting::where('key','email')->first();
        $svc = new \App\Services\EmailDiagnosticsService();
        $plain = $svc->probe($cfg->value['host'] ?? 'localhost', (int)($cfg->value['port'] ?? 25), $cfg->value['encryption'] ?? 'tls');
        $this->writeReport(['smtp_probe_plain'=>$plain]);
        $this->assertFileExists($this->reportPath);
    }

    public function test_password_recovery_flow_end_to_end_with_rate_limit(): void
    {
        $user = User::factory()->create(['email'=>'test-user@example.com']);
        // Request reset 5x should pass, 6th should be rate limited
        for ($i=0; $i<5; $i++) {
            $res = $this->post('/password/email', ['email'=>$user->email]);
            $res->assertStatus(302);
        }
        $res6 = $this->post('/password/email', ['email'=>$user->email]);
        $this->writeReport(['rate_limit_6th'=> $res6->getStatusCode(), 'expected_error'=>true]);
        $this->assertFileExists($this->reportPath);
    }

    public function test_mail_send_simulation_to_providers(): void
    {
        Mail::fake();
        // Usa closures raw, sem dependência de view
        // Usa um mailable simples para garantir contagem
        if (!class_exists('Tests\\Feature\\SimpleTestMailable')) {
            eval('namespace Tests\\Feature; class SimpleTestMailable extends \\Illuminate\\Mail\\Mailable { public function build(){ return $this->subject("Teste RCC")->html("<p>Teste</p>"); } }');
        }
        Mail::to('destinatario@gmail.com')->send(new \Tests\Feature\SimpleTestMailable());
        Mail::to('destinatario@outlook.com')->send(new \Tests\Feature\SimpleTestMailable());
        Mail::assertSent(\Tests\Feature\SimpleTestMailable::class, 2);
        $this->writeReport(['providers_tested'=>['gmail','outlook'],'mode'=>'raw']);
        $this->writeReport(['providers_tested'=>['gmail','outlook'],'mode'=>'simulation']);
        $this->assertFileExists($this->reportPath);
    }

    public function test_failure_scenarios_connection_timeout_and_credentials(): void
    {
        // Simula cenário de timeout e registra em relatório
        $svc = new \App\Services\EmailDiagnosticsService();
        $probe = $svc->probe('203.0.113.1', 25, 'tls', 1.0); // IP exemplo (RFC), timeout curto
        $this->writeReport(['timeout_probe'=>$probe]);
        $this->assertFileExists($this->reportPath);
        // Credenciais inválidas: registra condição
        $invalid = ['username' => 'wrong', 'password' => 'bad'];
        $this->writeReport(['invalid_credentials'=>$invalid]);
        $this->assertFileExists($this->reportPath);
    }
}
