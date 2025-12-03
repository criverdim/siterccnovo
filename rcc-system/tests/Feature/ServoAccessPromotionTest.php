<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServoAccessPromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_cards_shows_only_servos(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'role' => 'admin',
            'status' => 'active',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);

        $servo = User::factory()->create(['name' => 'Servo', 'is_servo' => true, 'status' => 'active']);
        $fiel = User::factory()->create(['name' => 'Fiel', 'is_servo' => false, 'status' => 'active']);

        $this->actingAs($admin);
        $res = $this->get('/api/v1/admin/users?is_servo=1&per_page=50');
        $res->assertStatus(200);
        $json = $res->json();
        $ids = collect($json['users'])->pluck('id');
        $this->assertTrue($ids->contains($servo->id));
        $this->assertFalse($ids->contains($fiel->id));
    }

    public function test_promotion_to_servo_reflects_in_cards(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'role' => 'admin',
            'status' => 'active',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);
        $user = User::factory()->create(['name' => 'User', 'is_servo' => false, 'status' => 'active']);

        $this->actingAs($admin);
        $this->put("/api/v1/admin/users/{$user->id}", ['is_servo' => true])->assertStatus(200);

        $res = $this->get('/api/v1/admin/users?is_servo=1&per_page=50');
        $res->assertStatus(200);
        $ids = collect($res->json()['users'])->pluck('id');
        $this->assertTrue($ids->contains($user->id));
    }

    public function test_register_accepts_optional_photo(): void
    {
        $file = \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg', 256, 256);
        $payload = [
            'name' => 'User',
            'email' => 'user@test.local',
            'phone' => '11999999999',
            'whatsapp' => '11999999999',
            'password' => 'secret123',
            'consent' => true,
        ];
        $res = $this->post('/register', array_merge($payload, ['photo' => $file]));
        $res->assertStatus(200);
        $res->assertJsonStructure(['status', 'user_id']);
        $u = User::where('email', 'user@test.local')->first();
        $this->assertNotNull($u);
        $this->assertTrue($u->photos()->exists());
        $this->assertTrue((bool) optional($u->activePhoto()->first())->is_active);
    }
}
