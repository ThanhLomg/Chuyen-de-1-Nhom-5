<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tự động tạo 50 sinh viên
        Student::factory(50)->create();
    }
}