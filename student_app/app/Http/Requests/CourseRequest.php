<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    // Bật true để cho phép người dùng gửi form
    public function authorize(): bool
    {
        return true; 
    }

    // Các luật bắt lỗi (Rules)
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Yêu cầu upload ảnh
        ];
    }

    // Tùy chỉnh thông báo lỗi tiếng Việt cho thân thiện
    public function messages(): array
    {
        return [
            'name.required' => 'Tên khóa học không được để trống.',
            'price.required' => 'Giá khóa học không được để trống.',
            'price.numeric' => 'Giá khóa học phải là số.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'image.image' => 'File tải lên phải là hình ảnh.',
        ];
    }
}