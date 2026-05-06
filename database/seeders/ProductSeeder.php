<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Produk Sample 1',
                'detail' => 'Produk contoh untuk data awal aplikasi.',
                'cover_image' => null,
            ],
            [
                'name' => 'Produk Sample 2',
                'detail' => 'Data sample kedua yang siap dipakai untuk testing CRUD.',
                'cover_image' => null,
            ],
            [
                'name' => 'Produk Sample 3',
                'detail' => 'Data bawaan agar halaman produk langsung memiliki isi.',
                'cover_image' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate([
                'name' => $product['name'],
            ], [
                'detail' => $product['detail'],
                'cover_image' => $product['cover_image'],
            ]);
        }
    }
}
