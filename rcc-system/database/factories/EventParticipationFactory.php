<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventParticipation>
 */
class EventParticipationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
            'ticket_uuid' => fake()->uuid(),
            'payment_method' => fake()->randomElement(['credit_card', 'debit_card', 'pix', 'cash']),
            'payment_status' => fake()->randomElement(['pending', 'approved', 'rejected', 'refunded']),
            'checked_in_at' => null,
        ];
    }

    /**
     * Confirmed participation
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'approved',
        ]);
    }

    /**
     * Pending participation
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'pending',
        ]);
    }
}
