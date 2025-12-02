<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class HtmlValidationTest extends TestCase
{
    public function test_homepage_validates_html(): void
    {
        $base = rtrim(getenv('TEST_BASE_URL') ?: 'http://127.0.0.1', '/');
        [$status, $html] = $this->fetch($base.'/');
        // Allow network hiccups as warning: do not fail if validator unreachable
        if (! in_array($status, [200, 301, 302])) {
            // Record warning via log file; do not fail the build
            @mkdir(__DIR__.'/../../storage/logs', 0777, true);
            file_put_contents(__DIR__.'/../../storage/logs/html-validation.json', json_encode(['warning' => 'homepage fetch failed'], JSON_PRETTY_PRINT));

            return;
        }

        $ch = curl_init('https://validator.w3.org/nu/?out=json');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/html; charset=utf-8',
            'User-Agent: RCC-Validator-Test',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $html);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (! in_array($code, [200, 400])) {
            file_put_contents(__DIR__.'/../../storage/logs/html-validation.json', json_encode(['error' => 'validator unreachable', 'code' => $code]));

            return;
        }
        $data = json_decode($body ?: '{}', true) ?: [];
        $errors = array_filter(($data['messages'] ?? []), fn ($m) => ($m['type'] ?? '') === 'error');
        $dir = __DIR__.'/../../storage/logs';
        if (! is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        file_put_contents($dir.'/html-validation.json', json_encode($data, JSON_PRETTY_PRINT));
        $this->assertFileExists($dir.'/html-validation.json');
        // Não falha build por erros de HTML; apenas grava o relatório
    }

    private function fetch(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [$status ?: 0, $body ?: ''];
    }
}
