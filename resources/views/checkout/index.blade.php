@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Thanh toán</h1>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Form thông tin giao hàng --}}
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-lg mb-4">Thông tin giao hàng</h3>

                    <div class="grid gap-4">
                        {{-- Họ tên --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Họ tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_name"
                                   value="{{ old('shipping_name', $user->name) }}"
                                   class="w-full border-gray-300 rounded-md @error('shipping_name') border-red-500 @enderror">
                            @error('shipping_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="shipping_phone"
                                   value="{{ old('shipping_phone', $user->phone) }}"
                                   class="w-full border-gray-300 rounded-md @error('shipping_phone') border-red-500 @enderror">
                            @error('shipping_phone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Địa chỉ --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_address"
                                   value="{{ old('shipping_address', $user->address) }}"
                                   class="w-full border-gray-300 rounded-md @error('shipping_address') border-red-500 @enderror">
                            @error('shipping_address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tỉnh/Thành phố --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Tỉnh/Thành phố <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_city"
                                   value="{{ old('shipping_city') }}"
                                   class="w-full border-gray-300 rounded-md @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Ghi chú --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Ghi chú</label>
                            <textarea name="notes" rows="3"
                                      class="w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <h3 class="font-semibold text-lg mt-6 mb-4">Phương thức thanh toán</h3>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" checked
                                   class="text-primary focus:ring-primary">
                            <div class="ml-3">
                                <span class="font-medium">Thanh toán khi nhận hàng (COD)</span>
                                <p class="text-sm text-gray-500">Bạn sẽ thanh toán khi nhận được hàng</p>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                   class="text-primary focus:ring-primary">
                            <div class="ml-3">
                                <span class="font-medium">Chuyển khoản ngân hàng</span>
                                <p class="text-sm text-gray-500">Chúng tôi sẽ gửi thông tin chuyển khoản qua email</p>
                            </div>
                        </label>
                        @error('payment_method')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tóm tắt đơn hàng --}}
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h3 class="font-semibold text-lg mb-4">Đơn hàng của bạn</h3>

                    <div class="max-h-64 overflow-y-auto mb-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center gap-3 py-2 border-b last:border-0">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                     class="w-12 h-12 object-cover rounded">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-500">x{{ $item['quantity'] }}</p>
                                </div>
                                <span class="text-sm font-medium">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính</span>
                            <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển</span>
                            <span>
                                @if($shipping > 0)
                                    {{ number_format($shipping, 0, ',', '.') }}đ
                                @else
                                    <span class="text-green-600 font-medium">Miễn phí</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-2 border-t mt-2">
                            <span>Tổng cộng</span>
                            <span class="text-primary">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-md mt-6 transition-colors">
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection