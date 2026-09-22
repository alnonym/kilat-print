<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Kilat Print',
            'email' => 'admin@kilatprint.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'no_hp' => '081234567890',
        ]);

        // Operator
        User::create([
            'name' => 'Operator Produksi',
            'email' => 'operator@kilatprint.com',
            'password' => bcrypt('password123'),
            'role' => 'operator',
            'no_hp' => '081987654321',
        ]);

        // Customer
        User::create([
            'name' => 'Pelanggan Demo',
            'email' => 'pelanggan@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
            'no_hp' => '085555555555',
        ]);

        // Categories
        $mug = \App\Models\Category::create(['name' => 'Mug Custom', 'slug' => 'mug-custom']);
        $banner = \App\Models\Category::create(['name' => 'Banner', 'slug' => 'banner']);
        $kaos = \App\Models\Category::create(['name' => 'Kaos Sablon', 'slug' => 'kaos-sablon']);

        // Products
        $product1 = \App\Models\Product::create([
            'category_id' => $mug->id,
            'name' => 'Mug Keramik Premium 400ml',
            'slug' => 'mug-keramik-premium-400ml',
            'description' => 'Mug berkualitas tinggi dari keramik pilihan, cocok untuk hadiah atau merchandise',
            'base_price' => 75000,
            'image' => 'products/mug-default.jpg',
        ]);

        $product2 = \App\Models\Product::create([
            'category_id' => $banner->id,
            'name' => 'Banner Vinyl 3x1m',
            'slug' => 'banner-vinyl-3x1m',
            'description' => 'Banner vinyl tahan cuaca, cetak digital full color resolusi tinggi',
            'base_price' => 450000,
            'image' => 'products/banner-default.jpg',
        ]);

        $product3 = \App\Models\Product::create([
            'category_id' => $kaos->id,
            'name' => 'Kaos Sablon DTG Premium',
            'slug' => 'kaos-sablon-dtg-premium',
            'description' => 'Kaos 100% cotton dengan sablon DTG berkualitas tinggi',
            'base_price' => 85000,
            'image' => 'products/kaos-default.jpg',
        ]);

        // Materials for Mug
        \App\Models\Material::create(['product_id' => $product1->id, 'name' => 'Keramik Putih', 'price_modifier' => 0]);
        \App\Models\Material::create(['product_id' => $product1->id, 'name' => 'Keramik Hitam', 'price_modifier' => 15000]);

        // Materials for Banner
        \App\Models\Material::create(['product_id' => $product2->id, 'name' => 'Vinyl 220gsm', 'price_modifier' => 0]);
        \App\Models\Material::create(['product_id' => $product2->id, 'name' => 'Vinyl 280gsm', 'price_modifier' => 50000]);

        // Materials for Kaos
        \App\Models\Material::create(['product_id' => $product3->id, 'name' => 'Cotton Standar', 'price_modifier' => 0]);
        \App\Models\Material::create(['product_id' => $product3->id, 'name' => 'Cotton Premium', 'price_modifier' => 25000]);

        // Finishings for Mug
        \App\Models\Finishing::create(['product_id' => $product1->id, 'name' => 'Tanpa Finishing', 'price_modifier' => 0]);
        \App\Models\Finishing::create(['product_id' => $product1->id, 'name' => 'Glossy', 'price_modifier' => 10000]);
        \App\Models\Finishing::create(['product_id' => $product1->id, 'name' => 'Matte', 'price_modifier' => 10000]);

        // Finishings for Banner
        \App\Models\Finishing::create(['product_id' => $product2->id, 'name' => 'Tanpa Finishing', 'price_modifier' => 0]);
        \App\Models\Finishing::create(['product_id' => $product2->id, 'name' => 'Laminating Glossy', 'price_modifier' => 75000]);
        \App\Models\Finishing::create(['product_id' => $product2->id, 'name' => 'Laminating Matte', 'price_modifier' => 75000]);

        // Finishings for Kaos
        \App\Models\Finishing::create(['product_id' => $product3->id, 'name' => 'Sablon Biasa', 'price_modifier' => 0]);
        \App\Models\Finishing::create(['product_id' => $product3->id, 'name' => 'Sablon 3D', 'price_modifier' => 20000]);
    }
}
