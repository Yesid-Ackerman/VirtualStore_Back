<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\Role::create(['name' => 'admin', 'description' => 'Administrador']);
    \App\Models\Role::create(['name' => 'empleado', 'description' => 'Empleado de almacén']);

    \App\Models\TransactionType::create(['name' => 'entrada']);
    \App\Models\TransactionType::create(['name' => 'salida']);
}
}
