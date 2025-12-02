<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentLog>
 */
class PaymentLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_id' => fake()->uuid(),
            'status' => fake()->randomElement(['approved', 'rejected', 'pending', 'refunded']),
            'payment_method' => fake()->randomElement(['credit_card', 'debit_card', 'pix', 'boleto']),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'installments' => fake()->numberBetween(1, 12),
            'mercadopago_data' => [
                'id' => fake()->uuid(),
                'status' => fake()->randomElement(['approved', 'rejected']),
                'payment_method' => fake()->randomElement(['credit_card', 'debit_card']),
            ],
            'processed_at' => now(),
        ];
    }

    /**
     * Approved payment
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Rejected payment
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
