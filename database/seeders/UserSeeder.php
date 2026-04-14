<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'nijeanlionel@gmail.com',
            'password' => Hash::make('password'),
        ]); 

        User::create([
            'name' => 'User',
            'email' => 'irumvabric@gmail.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Niyo Jean',
            'email' => 'niyojean303030@gmail.com',
            'password' => Hash::make('303030'),
        ]);

    
    }
}
