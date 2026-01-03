<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
*/
class PhotoFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        
        $images = [
            "https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&sig=1",
            "https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=800&sig=2",
            "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&sig=3",
            "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&sig=4",
            "https://images.unsplash.com/photo-1495433324511-bf8e92934d90?w=800&sig=5",
            "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&sig=6",
            "https://images.unsplash.com/photo-1510552776732-01acc9a4c47e?w=800&sig=7",
            "https://images.unsplash.com/photo-1512499617640-c2f999018b72?w=800&sig=8",
            "https://images.unsplash.com/photo-1563906267088-b029e7101114?w=800&sig=9",
            "https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&sig=10",
            
            "https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&sig=11",
            "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&sig=12",
            "https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&sig=13",
            "https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=800&sig=14",
            "https://images.unsplash.com/photo-1503602642458-232111445657?w=800&sig=15",
            "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&sig=16",
            "https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=800&sig=17",
            "https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&sig=18",
            "https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&sig=19",
            "https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=800&sig=20"
        ];
        
        // Exemple d'utilisation
        $randomImage = $images[array_rand($images)];
    
        
        return [
            'url' => $randomImage,
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
