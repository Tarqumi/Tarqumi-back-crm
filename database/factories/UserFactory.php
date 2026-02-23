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
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->numerify('+966-5#-###-####'),
            'whatsapp' => fake()->numerify('+966-5#-###-####'),
            'department' => fake()->randomElement([
                'Engineering', 'Sales', 'Marketing', 'HR', 'Finance',
            ]),
            'job_title' => fake()->jobTitle(),
            'role' => 'employee',
            'is_active' => true,
            'last_active_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function founder(string $founderRole = 'ceo'): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'founder',
            'founder_role' => $founderRole,
        ]);
    }

    public function hr(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'hr',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'inactive_days' => 30,
        ]);
    }
}
