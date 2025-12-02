<?php

namespace Database\Factories;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        return [
            'target_user_id' => \App\Models\User::factory(),
            'group_id' => \App\Models\Group::factory(),
            'scheduled_at' => $this->faker->dateTimeBetween('+1 day', '+2 weeks'),
            'team' => [
                ['user_id' => \App\Models\User::factory()],
            ],
            'report' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['scheduled', 'done', 'cancelled']),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
