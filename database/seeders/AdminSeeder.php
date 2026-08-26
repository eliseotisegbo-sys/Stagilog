<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur TFG',
            'email' => 'admin@tfg-sarl.com',
            'password' => Hash::make('Admin@2026'), // À changer à la première connexion
            'role' => 'admin',
            'first_login' => true,
        ]);
    }
}
