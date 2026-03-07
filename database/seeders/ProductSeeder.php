<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Produk Sample 1',
                'detail' => 'Produk contoh untuk data awal aplikasi.',
            ],
            [
                'name' => 'Produk Sample 2',
                'detail' => 'Data sample kedua yang siap dipakai untuk testing CRUD.',
            ],
            [
                'name' => 'Produk Sample 3',
                'detail' => 'Data bawaan agar halaman produk langsung memiliki isi.',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate([
                'name' => $product['name'],
            ], [
                'detail' => $product['detail'],
            ]);
        }
    }
}
