<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierProduct>
 */
class SupplierProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'purchase_price' => $this->faker->optional()->randomFloat(2, 1, 2000),
            'date_purchased' => $this->faker->optional()->date(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
