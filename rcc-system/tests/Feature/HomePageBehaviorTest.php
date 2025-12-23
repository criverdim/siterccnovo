<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageBehaviorTest extends TestCase
{
    public function test_homepage_assets_and_carousel(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = (string) $response->getContent();

        // Basic assertions for assets and structure
        $this->assertStringContainsString('carousel-container', $html);
        $this->assertStringContainsString('carousel-slide', $html);
        $this->assertStringContainsString('Grupo de Oração', $html);

        // Validate that build assets are referenced
        $this->assertMatchesRegularExpression('/\/build\/assets\/app-.*\.css/', $html);
        $this->assertMatchesRegularExpression('/\/build\/assets\/app-.*\.js/', $html);
    }
}
