<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::withoutEvents(function () {
            Product::create([
            'id' => 1,
            'name'        => 'Celular Motorola G30',
            'category_id' => 1, // Electrónica
            'brand'       => 'Motorola',
            'model'       => 'G30',
            'sku'         => 'MOTO-G30-001',
            'price_buy'   => 650000,
            'price_sell'  => 750000,
            'stock'       => 10,
            'status'      => true,
            'avatar'      => 'products/motorola_g30.png',
        ]);
            Product::create([
                'id' => 2,
                'name'        => 'Camiseta Negra Básica',
                'category_id' => 1, // Ropa
                'brand'       => 'Genérica',
                'model'       => null,
                'sku'         => 'CAM-NEG-001',
                'price_buy'   => 20000,
                'price_sell'  => 40000,
                'stock'       => 50,
                'status'      => true,
                'avatar'      => 'products/camiseta_negra.png',
            ]);
        });
    }
}

