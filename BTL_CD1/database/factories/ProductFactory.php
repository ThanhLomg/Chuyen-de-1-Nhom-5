<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $furnitureNames = [
            'Sofa góc', 'Ghế bành', 'Bàn trà', 'Kệ tivi', 'Tủ giày', 'Giường ngủ', 'Tủ quần áo', 'Bàn trang điểm',
            'Bàn ăn', 'Ghế ăn', 'Tủ bếp', 'Kệ bếp', 'Bàn làm việc', 'Ghế xoay', 'Kệ sách', 'Tủ hồ sơ',
            'Gương phòng tắm', 'Kệ phòng tắm', 'Bộ bàn ghế sân vườn', 'Xích đu', 'Tủ đựng đồ', 'Kệ đa năng',
            'Đèn trang trí', 'Thảm trải sàn', 'Rèm cửa', 'Tranh treo tường', 'Đồng hồ treo tường', 'Kệ treo tường',
            'Bàn console', 'Ghế đôn'
        ];

        $price = $this->faker->numberBetween(500000, 50000000);
        $salePrice = $this->faker->boolean(30) 
            ? (int) ($price * (1 - $this->faker->randomFloat(2, 0.05, 0.3))) 
            : null;

        $colors = ['Trắng', 'Đen', 'Nâu', 'Gỗ tự nhiên', 'Xám', 'Kem'];
        $materials = ['Gỗ thông', 'MDF', 'Gỗ sồi', 'Inox', 'Nhựa', 'Vải'];

        $name = $this->faker->randomElement($furnitureNames) . ' ' . $this->faker->word();

        return [
            'name'              => $name,
            'slug'              => Str::slug($name) . '-' . Str::random(4),
            'description'       => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->sentence(),
            'price'             => $price,
            'sale_price'        => $salePrice,
            'stock'             => $this->faker->numberBetween(0, 100),
            'image'             => 'products/demo.jpg',
            'gallery'           => ['products/demo1.jpg', 'products/demo2.jpg'],
            'material'          => $this->faker->randomElement($materials),
            'dimensions'        => $this->faker->numberBetween(50, 200) . 'x' . 
                                   $this->faker->numberBetween(30, 100) . 'x' . 
                                   $this->faker->numberBetween(40, 120) . ' cm',
            'color'             => $this->faker->randomElement($colors),
            'brand'             => $this->faker->company(),
            'views'             => $this->faker->numberBetween(0, 1000),
            'is_featured'       => $this->faker->boolean(20),
            'is_active'         => true,
        ];
    }
}