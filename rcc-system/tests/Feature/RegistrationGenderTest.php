<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Tests\TestCase;

class RegistrationGenderTest extends TestCase
{
    public function test_registration_persists_gender_male(): void
    {
        $group = Group::create([
            'name' => 'Grupo Teste',
            'weekday' => 'Quarta',
            'time' => '19:30',
            'address' => 'Rua Teste, 123',
        ]);

        $payload = [
            'name' => 'Cadastro Gênero Masculino',
            'email' => 'genero.masc@example.com',
            'phone' => '11999999999',
            'whatsapp' => '11999999999',
            'password' => 'secret123',
            'consent' => '1',
            'groups' => [$group->id],
            'gender' => 'male',
        ];

        $res = $this->postJson('/register', $payload);
        $res->assertOk()->assertJsonStructure(['status', 'user_id']);

        $user = User::where('email', 'genero.masc@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('male', $user->gender);
    }

    public function test_registration_rejects_invalid_gender(): void
    {
        $group = Group::create([
            'name' => 'Grupo Teste 2',
            'weekday' => 'Quinta',
            'time' => '20:00',
            'address' => 'Rua Exemplo, 456',
        ]);

        $payload = [
            'name' => 'Cadastro Gênero Inválido',
            'email' => 'genero.invalido@example.com',
            'phone' => '11888888888',
            'whatsapp' => '11888888888',
            'password' => 'secret123',
            'consent' => '1',
            'groups' => [$group->id],
            'gender' => 'other',
        ];

        $res = $this->postJson('/register', $payload);
        $res->assertStatus(422)->assertJsonValidationErrors(['gender']);
    }
}
