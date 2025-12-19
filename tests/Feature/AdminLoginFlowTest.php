<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_accessing_admin_redirects_to_main_login_with_redirect(): void
    {
        $res = $this->get('/admin');
        $res->assertStatus(302);
        $res->assertRedirect('/login?area=admin&redirect=%2Fadmin');
    }

    public function test_admin_can_login_via_main_login_and_access_admin(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'criverdim@hotmail.com',
            'password' => Hash::make('Verdi123@'),
            'status' => 'active',
            'role' => 'admin',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'criverdim@hotmail.com',
            'password' => 'Verdi123@',
            'area' => 'admin',
            'redirect' => '/admin',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');

        $this->be($user);
        $panel = $this->get('/admin');
        $panel->assertStatus(200);
    }
}

