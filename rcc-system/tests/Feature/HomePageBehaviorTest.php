<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class HomePageBehaviorTest extends TestCase
{
    public function test_homepage_assets_and_carousel(): void
    {
        $base = rtrim(getenv('TEST_BASE_URL') ?: 'http://127.0.0.1', '/');
        [$status, $html] = $this->fetch($base . '/');
        $this->assertTrue(in_array($status, [200, 301, 302]), 'Homepage not reachable');

        // Basic assertions for assets and structure
        $this->assertStringContainsString('carousel-container', $html);
        $this->assertStringContainsString('carousel-slide', $html);
        $this->assertStringContainsString('Grupo de Oração', $html);

        // Validate that build assets are referenced
        $this->assertMatchesRegularExpression('/\/build\/assets\/app-.*\.css/', $html);
        $this->assertMatchesRegularExpression('/\/build\/assets\/app-.*\.js/', $html);
    }

    private function fetch(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$status ?: 0, $body ?: ''];
    }
}
