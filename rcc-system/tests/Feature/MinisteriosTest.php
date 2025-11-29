<?php

namespace Tests\Feature;

use App\Models\Ministry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MinisteriosTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_ministerio(): void
    {
        $ministry = Ministry::create([
            'name' => 'Música',
            'description' => 'Serviço de música',
            'is_active' => true,
        ]);

        $this->assertNotNull($ministry->id);
        $this->assertEquals('Música', $ministry->name);
    }

    public function test_usuario_servo_pode_vincular_ministerios(): void
    {
        $music = Ministry::create(['name' => 'Música', 'is_active' => true]);
        $intercession = Ministry::create(['name' => 'Intercessão', 'is_active' => true]);

        $user = User::factory()->create(['is_servo' => true]);
        $user->ministries()->attach([$music->id, $intercession->id]);

        $this->assertCount(2, $user->ministries);
        $this->assertTrue($user->ministries->pluck('name')->contains('Música'));
        $this->assertTrue($user->ministries->pluck('name')->contains('Intercessão'));
    }
}
