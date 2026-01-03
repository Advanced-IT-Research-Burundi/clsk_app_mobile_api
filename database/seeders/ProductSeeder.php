<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if not exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        // Create some types
        $types = \App\Models\Type::factory()->count(3)->create();

        // Create categories for each type
        $categories = collect();
        foreach ($types as $type) {
            $categories = $categories->merge(
                \App\Models\Category::factory()->count(3)->create([
                    'user_id' => $user->id,
                    'type_id' => $type->id,
                ])
            );
        }

        // Create devises
        $devises = \App\Models\Devise::factory()->count(2)->create();

        // Create products
        foreach ($categories as $category) {
             \App\Models\Product::factory()->count(5)->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'devise_id' => $devises->random()->id,
            ])->each(function ($product) {
                // Attach photos to each product
                \App\Models\Photo::factory()->count(3)->create([
                    'product_id' => $product->id,
                ]);
            });
        }
    }
}
