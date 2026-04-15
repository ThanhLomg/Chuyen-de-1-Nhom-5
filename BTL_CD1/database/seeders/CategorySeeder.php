<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Phòng khách',
            'Phòng ngủ',
            'Phòng ăn',
            'Phòng làm việc',
            'Phòng tắm',
            'Nhà bếp',
            'Ngoài trời',
            'Lưu trữ & Tủ',
        ];

        foreach ($names as $index => $name) {
            Category::create([
                'name'       => $name,
                'slug'       => Str::slug($name),
                'is_active'  => true,
                'sort_order' => $index,
            ]);
        }
    }
}