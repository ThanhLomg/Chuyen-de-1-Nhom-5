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
    Schema::create('enrollments', function (Blueprint $table) {
        $table->id();
        
        // Cách chuẩn: Chỉ cần truyền tên bảng vào constrained()
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Thêm cascade cho student luôn cho an toàn
        
        $table->timestamps();

        // Index kết hợp 2 cột (Composite Index)
        $table->unique(['course_id', 'student_id']); // Nên dùng unique thay vì index thường để chống đăng ký trùng ở tầng DB
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
