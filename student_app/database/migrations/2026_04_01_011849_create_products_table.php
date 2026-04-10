<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Tên sản phẩm [cite: 18]
        $table->decimal('price', 15, 2); // Giá [cite: 19]
        $table->integer('quantity'); // Số lượng [cite: 20]
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Danh mục [cite: 21]
        $table->string('image')->nullable(); // Ảnh upload [cite: 22]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
