<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->word(),
            'value' => [
                'config' => fake()->word(),
                'setting' => fake()->word(),
            ],
        ];
    }

    /**
     * Email settings
     */
    public function email(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'email',
            'value' => [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'username' => 'test@example.com',
                'password' => 'password123',
                'encryption' => 'tls',
                'from_email' => 'noreply@example.com',
                'from_name' => 'RCC System',
            ],
        ]);
    }

    /**
     * MercadoPago settings
     */
    public function mercadopago(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'mercadopago',
            'value' => [
                'access_token' => 'TEST-123456789',
                'public_key' => 'TEST-987654321',
                'client_id' => '123456',
                'client_secret' => 'secret-key',
                'sandbox' => true,
            ],
        ]);
    }
}