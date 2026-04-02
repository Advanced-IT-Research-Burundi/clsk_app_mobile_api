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
        $user1 = User::create([
            'name' => 'Admin',
            'email' => 'nijeanlionel@gmail.com',
            'password' => Hash::make('password'),
        ]); 

        $user2 = User::create([
            'name' => 'User',
            'email' => 'irumvabric@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
