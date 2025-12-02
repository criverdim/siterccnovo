<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->randomElement(['fiel', 'servo', 'admin']);
        $status = 'active';

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'role' => $role,
            'status' => $status,
            'can_access_admin' => false,
            'is_master_admin' => false,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (User $user) {
                if ($user->role === 'admin') {
                    $user->can_access_admin = true;
                }
            })
            ->afterCreating(function (User $user) {
                if ($user->role === 'admin' && ! $user->can_access_admin) {
                    $user->forceFill(['can_access_admin' => true])->save();
                }
            });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
