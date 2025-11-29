<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'name' => 'Evento '.fake()->words(2, true),
            'description' => '<p>'.fake()->sentence(12).'</p>',
            'location' => fake()->streetAddress(),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'start_time' => '19:00:00',
            'end_time' => '21:00:00',
            'is_paid' => false,
            'price' => 0,
            'generates_ticket' => true,
            'allows_online_payment' => true,
            'capacity' => 100,
            'show_on_homepage' => true,
            'is_active' => true,
        ];
    }
}

