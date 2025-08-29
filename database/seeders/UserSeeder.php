<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Observers\ModelObserver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar temporalmente los observers/logs
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('123456'),
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
            'role_id' => 3
        ]);
    }
}
