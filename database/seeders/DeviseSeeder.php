<?php

namespace Database\Seeders;

use App\Models\Devise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devises = [
            ['name' => 'Dollar', 'code' => 'USD', 'symbol' => '$'],
            ['name' => 'Bif', 'code' => 'BIF', 'symbol' => 'Frw'],
            ['name' => 'Yuan', 'code' => 'RMB', 'symbol' => '¥'],
        ];
        foreach ($devises as $devise) {
            Devise::create($devise);
        }
    }
}
