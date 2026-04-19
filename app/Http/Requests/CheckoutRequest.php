<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => ['required', 'regex:/^0[35789][0-9]{8}$/'],
            'shipping_address' => 'required|string|max:255',
            'shipping_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,bank_transfer',
            'notes'            => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_phone.regex' => 'Số điện thoại không hợp lệ (VD: 0912345678).',
            'payment_method.in'    => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}