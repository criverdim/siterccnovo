<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeLoginStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_register_and_login_for_guests(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Cadastro');
        $response->assertSee('Login');
        $response->assertSee('Você está deslogado');
    }

    public function test_home_shows_username_and_logout_for_authenticated(): void
    {
        $user = User::factory()->create(['name' => 'Maria Teste']);
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Logado como');
        $response->assertSee('Maria Teste');
        $response->assertSee('Logout');
        $response->assertDontSee('Cadastro');
        $response->assertDontSee('Login');
    }
}
