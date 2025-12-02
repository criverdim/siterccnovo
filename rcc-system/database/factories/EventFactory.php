<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' - Evento',
            'category' => fake()->randomElement(['retiro', 'encontro', 'culto', 'congresso', 'vigilia']),
            'description' => fake()->paragraph(3),
            'location' => fake()->address(),
            'start_date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'end_date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'days_count' => fake()->numberBetween(1, 7),
            'min_age' => fake()->numberBetween(12, 18),
            'is_paid' => fake()->boolean(30),
            'price' => fake()->randomFloat(2, 10, 500),
            'parceling_enabled' => fake()->boolean(50),
            'parceling_max' => fake()->numberBetween(3, 12),
            'coupons_enabled' => fake()->boolean(30),
            'has_coffee' => fake()->boolean(60),
            'has_lunch' => fake()->boolean(40),
            'generates_ticket' => true,
            'allows_online_payment' => true,
            'capacity' => fake()->numberBetween(20, 500),
            'show_on_homepage' => fake()->boolean(70),
            'is_active' => fake()->boolean(80),
            'extra_services' => [],
            'terms' => fake()->paragraph(2),
            'rules' => fake()->paragraph(2),
            'photos' => null,
            'map_embed_url' => null,
            'arrival_info' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the event is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_paid' => true,
            'price' => fake()->randomFloat(2, 50, 500),
        ]);
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
