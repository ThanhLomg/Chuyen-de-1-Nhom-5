<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    // Bắt buộc phải có để cho phép request này được chạy
    public function authorize() {
        return true; 
    }

    public function rules() {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:draft,published',
            'slug' => 'nullable',
        ];
    }

    public function messages() {
        return [
            'price.min' => 'Giá phải lớn hơn 0!',
            'price.numeric' => 'Giá không hợp lệ!',
            'image.required' => 'Hình ảnh khóa học là bắt buộc!',
        ];
    }
}