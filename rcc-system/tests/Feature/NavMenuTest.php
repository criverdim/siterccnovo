<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_has_navigation_semantics_and_accessibility(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ir para conteúdo principal');
        $response->assertSee('aria-controls="mobileMenu"', false);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('class="nav-link', false);
        $response->assertSee('role="navigation"', false);
    }

    public function test_links_are_present_and_work(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('Eventos');
        $this->get('/events')->assertStatus(200);
        $this->get('/groups')->assertStatus(200);
        $this->get('/calendar')->assertStatus(200);
    }

    public function test_mobile_menu_markup_exists(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="mobileMenu"', false);
        $response->assertSee('id="menuBtn"', false);
    }

    public function test_active_state_on_events_page(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('nav-link-active', false);
    }

    public function test_home_loads_within_reasonable_time(): void
    {
        $start = microtime(true);
        $response = $this->get('/');
        $response->assertStatus(200);
        $elapsedMs = (microtime(true) - $start) * 1000;
        $this->assertTrue($elapsedMs < 3000, 'Página inicial levou '.number_format($elapsedMs, 2).'ms para carregar');
    }
}
