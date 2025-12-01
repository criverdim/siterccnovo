<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' - Grupo de Oração',
            'description' => fake()->paragraph(),
            'responsible' => fake()->name(),
            'responsible_phone' => fake()->phoneNumber(),
            'responsible_whatsapp' => fake()->phoneNumber(),
            'responsible_email' => fake()->email(),
            'weekday' => fake()->randomElement(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            'time' => fake()->time(),
            'address' => fake()->address(),
            'photos' => null,
            'cover_photo' => null,
            'cover_bg_color' => fake()->hexColor(),
            'cover_object_position' => 'center',
        ];
    }
}