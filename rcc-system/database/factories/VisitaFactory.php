<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visita>
 */
class VisitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->optional()->email(),
            'phone' => fake()->optional()->phoneNumber(),
            'event_type' => fake()->randomElement(['culto', 'evento_especial', 'grupo_oracao', 'outro']),
            'first_time' => fake()->boolean(60),
            'returned' => fake()->boolean(30),
            'how_did_you_hear' => fake()->randomElement(['amigo', 'familia', 'internet', 'redes_sociais', 'convite', 'outro']),
            'prayer_request' => fake()->optional()->paragraph(),
            'notes' => fake()->optional()->paragraph(),
            'visit_date' => fake()->dateTimeThisYear(),
        ];
    }

    /**
     * First time visitor
     */
    public function firstTime(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_time' => true,
            'returned' => false,
        ]);
    }

    /**
     * Returned visitor
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_time' => false,
            'returned' => true,
        ]);
    }
}