<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        User::create([
            'name' => 'Yesid Admin',
            'email' => 'admin@store.com',
            'password' => Hash::make('password'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'Manager Prueba',
            'email' => 'manager@store.com',
            'password' => Hash::make('password'),
            'role_id' => 2
        ]);

           User::create([
            'name' => 'Vendedor Prueba',
            'email' => 'vendedor@store.com',
            'password' => Hash::make('password'),
            'role_id' => 2
        ]);
    }
}
