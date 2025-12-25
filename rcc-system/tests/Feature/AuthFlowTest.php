<?php

namespace Tests\Feature;

use App\Models\Ministry;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    public function test_register_member_and_login_member_area(): void
    {
        $payload = [
            'name' => 'Teste Membro',
            'email' => 'membro@example.com',
            'phone' => '11999999999',
            'whatsapp' => '11999999999',
            'cep' => '14400000',
            'address' => 'Rua Teste',
            'number' => '123',
            'district' => 'Centro',
            'city' => 'Miguelópolis',
            'state' => 'SP',
            'password' => 'secret123',
            'consent' => '1',
            'gender' => 'male',
        ];

        $res = $this->post('/register', $payload);
        $res->assertStatus(200)->assertJsonStructure(['status', 'user_id']);

        $login = $this->post('/login', [
            'email' => 'membro@example.com',
            'password' => 'secret123',
            'area' => 'membro',
        ]);

        $login->assertRedirect('/area/membro');
    }

    public function test_register_servo_with_ministry_and_login_servo_area(): void
    {
        $ministry = Ministry::create(['name' => 'Música']);

        $payload = [
            'name' => 'Teste Servo',
            'email' => 'servo@example.com',
            'phone' => '11988888888',
            'whatsapp' => '11988888888',
            'cep' => '14400000',
            'address' => 'Rua Teste',
            'number' => '123',
            'district' => 'Centro',
            'city' => 'Miguelópolis',
            'state' => 'SP',
            'password' => 'secret123',
            'is_servo' => 1,
            'ministries' => [$ministry->id],
            'consent' => '1',
            'gender' => 'male',
        ];

        $res = $this->post('/register', $payload);
        $res->assertStatus(200)->assertJsonStructure(['status', 'user_id']);

        $login = $this->post('/login', [
            'email' => 'servo@example.com',
            'password' => 'secret123',
            'area' => 'servo',
        ]);

        $login->assertRedirect('/area/servo');
    }

    public function test_servo_login_blocked_when_user_not_servo(): void
    {
        $user = User::create([
            'name' => 'Não Servo',
            'email' => 'naoservo@example.com',
            'password' => Hash::make('secret123'),
            'phone' => '11977777777',
            'whatsapp' => '11977777777',
            'is_servo' => false,
        ]);

        $res = $this->post('/login', [
            'email' => 'naoservo@example.com',
            'password' => 'secret123',
            'area' => 'servo',
        ]);

        $res->assertSessionHasErrors('area');
    }
}
