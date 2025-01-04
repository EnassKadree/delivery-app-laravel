<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
{
    return [
        'email' => fake()->unique()->safeEmail(),
        'phone' => fake()->unique()->phoneNumber(),
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'customer',
        'remember_token' => Str::random(10),
    ];
}


    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
