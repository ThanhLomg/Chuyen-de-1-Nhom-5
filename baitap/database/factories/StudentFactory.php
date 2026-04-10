<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Sinh ngẫu nhiên tên người
            'name' => fake()->name(), 
            // Sinh ngẫu nhiên email an toàn, không trùng lặp
            'email' => fake()->unique()->safeEmail(), 
        ];
    }
}