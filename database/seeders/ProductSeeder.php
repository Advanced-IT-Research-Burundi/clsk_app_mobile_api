<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Devise;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\User;
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
        $user =  User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        // Create some types
        $types =  Type::factory()->count(3)->create();

        // Create categories for each type
        $categories = collect();
        foreach ($types as $type) {
            $categories = $categories->merge(
                 Category::factory()->count(3)->create([
                    'user_id' => $user->id,
                    'type_id' => $type->id,
                ])
            );
        }

        // Create devises
        $devises =  Devise::factory()->count(2)->create();

        // Create products
        foreach ($categories as $category) {
              Product::factory()->count(5)->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'devise_id' => $devises->random()->id,
            ])->each(function ($product) {
                // Attach photos to each product
                 Photo::factory()->count(3)->create([
                    'product_id' => $product->id,
                ]);
            });
        }

        Supplier::factory()->count(5);

      
    }
}
