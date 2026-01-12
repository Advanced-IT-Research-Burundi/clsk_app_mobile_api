<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->numberBetween(1, 100),
            'packaging' => $this->faker->randomElement(['unit', 'box', 'pallet']),
            'exchange_rate' => $this->faker->randomFloat(2, 0.5, 2.0),
            'date' => $this->faker->date(),
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'devise_id' => \App\Models\Devise::factory(),
            'unit_per_package' => $this->faker->numberBetween(1, 12),
        ];
    }
}
