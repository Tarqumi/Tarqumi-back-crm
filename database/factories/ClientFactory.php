<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company_name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('+966-#-###-####'),
            'whatsapp' => fake()->numerify('+966-5#-###-####'),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'industry' => fake()->randomElement([
                'Technology', 'Finance', 'Healthcare',
                'Retail', 'Manufacturing', 'Education',
                'Real Estate', 'Consulting',
            ]),
            'notes' => fake()->paragraph(),
            'is_active' => true,
            'is_default' => false,
        ];
    }

    /**
     * Default "Tarqumi" client state.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Tarqumi',
            'company_name' => 'Tarqumi',
            'email' => 'info@tarqumi.com',
            'is_default' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
