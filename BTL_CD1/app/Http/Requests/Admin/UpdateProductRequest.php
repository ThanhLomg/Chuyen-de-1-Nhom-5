<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|integer|min:1000',
            'sale_price'        => 'nullable|integer|min:1000|lt:price',
            'stock'             => 'required|integer|min:0|max:99999',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'gallery.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'gallery'           => 'nullable|array|max:6',
            'material'          => 'nullable|string|max:100',
            'dimensions'        => 'nullable|string|max:100',
            'color'             => 'nullable|string|max:50',
            'brand'             => 'nullable|string|max:100',
            'is_featured'       => 'boolean',
            'is_active'         => 'boolean',
            'remove_gallery'    => 'nullable|array',
            'remove_gallery.*'  => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'description.min' => 'Mô tả phải có ít nhất 20 ký tự.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.min' => 'Giá sản phẩm phải lớn hơn 1,000đ.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'image.image' => 'File phải là ảnh.',
            'image.max' => 'Ảnh không được vượt quá 3MB.',
            'gallery.*.image' => 'File trong thư viện phải là ảnh.',
            'gallery.max' => 'Chỉ được tải tối đa 6 ảnh.',
        ];
    }
}