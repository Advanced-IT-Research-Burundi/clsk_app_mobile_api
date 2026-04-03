<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Autres', 'user_id' => 1],
            ['name' => 'Alimentation', 'user_id' => 1],
            ['name' => 'Electronique', 'user_id' => 1],
            ['name' => 'Vetements', 'user_id' => 1],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
