<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        // Tạo 30 sản phẩm, gán category ngay khi tạo
        for ($i = 0; $i < 30; $i++) {
            Product::factory()->create([
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}