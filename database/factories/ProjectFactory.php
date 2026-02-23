<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+12 months');

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'budget' => fake()->randomFloat(2, 1000, 500000),
            'currency' => fake()->randomElement(['SAR', 'USD', 'EUR', 'GBP', 'AED']),
            'priority' => fake()->numberBetween(1, 10),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'estimated_hours' => fake()->randomFloat(2, 20, 2000),
            'status' => fake()->randomElement([
                'planning', 'analysis', 'design',
                'implementation', 'testing', 'deployment',
            ]),
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'status' => 'implementation',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => now()->subDays(30),
            'status' => 'implementation',
            'is_active' => true,
        ]);
    }

    public function planning(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'planning',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'deployment',
        ]);
    }
}
