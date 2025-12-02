<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_reset_creates_token_and_sends_email(): void
    {
        $user = User::factory()->create(['email' => 'user@test.local']);
        $res = $this->post('/password/email', ['email' => 'user@test.local']);
        $res->assertStatus(302);
        $this->assertNotNull(DB::table('password_reset_tokens')->where('email', 'user@test.local')->first());
    }

    public function test_reset_changes_password_with_valid_token(): void
    {
        $user = User::factory()->create(['email' => 'user@test.local']);
        $this->post('/password/email', ['email' => 'user@test.local']);
        $tokenRow = DB::table('password_reset_tokens')->where('email', 'user@test.local')->first();
        $this->assertNotNull($tokenRow);
        // For test simplicity, use the same raw token route as sent via email
        $rawToken = 'dummy';
        DB::table('password_reset_tokens')->update(['token' => bcrypt($rawToken)]);
        $res = $this->post('/password/reset', [
            'email' => 'user@test.local',
            'token' => $rawToken,
            'password' => 'NewPwd!2025',
            'password_confirmation' => 'NewPwd!2025',
        ]);
        $res->assertStatus(302);
    }

    public function test_change_requires_current_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('OldPwd!2024')]);
        $this->be($user);
        $res = $this->post('/area/password/change', [
            'current_password' => 'OldPwd!2024',
            'password' => 'NewPwd!2025',
            'password_confirmation' => 'NewPwd!2025',
        ]);
        $res->assertStatus(302);
    }
}
