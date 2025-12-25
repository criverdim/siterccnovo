<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DuplicateRegistrationTest extends TestCase
{
    public function test_realtime_check_detects_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'dup@example.com',
            'phone' => '11999999999',
            'whatsapp' => '11999999999',
            'cpf' => '12345678901',
            'status' => 'active',
        ]);

        $res = $this->postJson('/api/register/check', [
            'email' => 'dup@example.com',
        ]);

        $res->assertOk()
            ->assertJson([
                'duplicate' => true,
            ])
            ->assertJsonStructure(['duplicate', 'possible_duplicate', 'reasons', 'message', 'links' => ['login', 'password_forgot']]);
    }

    public function test_register_rejects_when_email_already_exists(): void
    {
        User::factory()->create([
            'email' => 'exists@example.com',
            'phone' => '11911111111',
            'whatsapp' => '11911111111',
            'cpf' => '11122233344',
            'status' => 'active',
        ]);

        $payload = [
            'name' => 'Novo Cadastro',
            'email' => 'exists@example.com',
            'phone' => '11911111111',
            'whatsapp' => '11911111111',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'consent' => '1',
            'gender' => 'male',
            'cep' => '14400000',
            'address' => 'Rua Teste',
            'number' => '123',
            'district' => 'Centro',
            'city' => 'Miguelópolis',
            'state' => 'SP',
        ];

        $res = $this->postJson('/register', $payload);

        $res->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_realtime_check_can_warn_on_similar_name_and_birth_date(): void
    {
        User::factory()->create([
            'name' => 'Maria Aparecida de Souza',
            'email' => 'maria@example.com',
            'phone' => '11922222222',
            'whatsapp' => '11922222222',
            'birth_date' => '2000-01-01',
            'status' => 'active',
        ]);

        $res = $this->postJson('/api/register/check', [
            'name' => 'Maria Aparecida Souza',
            'birth_date' => '2000-01-01',
        ]);

        $res->assertOk()->assertJson([
            'possible_duplicate' => true,
        ]);
    }

    public function test_realtime_check_query_count_is_small_with_large_volume(): void
    {
        User::factory()->count(2000)->create();

        DB::flushQueryLog();
        DB::enableQueryLog();

        $res = $this->postJson('/api/register/check', [
            'email' => 'notfound@example.com',
        ]);

        $res->assertOk()->assertJson([
            'duplicate' => false,
        ]);

        $queries = DB::getQueryLog();
        $this->assertLessThanOrEqual(3, count($queries));
    }
}
