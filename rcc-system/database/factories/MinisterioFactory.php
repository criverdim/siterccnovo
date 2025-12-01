<?php

namespace Database\Factories;

use App\Models\Ministerio;
use Illuminate\Database\Eloquent\Factories\Factory;

class MinisterioFactory extends Factory
{
    protected $model = Ministerio::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Servo', 'Diácono', 'Presbítero', 'Evangelista', 'Pastor', 'Apóstolo', 'Professor', 'Missionário']),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}