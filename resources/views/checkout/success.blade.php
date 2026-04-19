@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        {{-- Icon thành công --}}
        <div class="mx-auto h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
            <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-600 mb-6">Cảm ơn bạn đã mua hàng tại FurniShop.</p>

        {{-- Mã đơn hàng --}}
        <div class="bg-gray-100 inline-block px-6 py-3 rounded-lg mb-6">
            <span class="text-gray-600">Mã đơn hàng:</span>
            <span class="font-mono font-bold text-lg ml-2">{{ $order->order_number }}</span>
        </div>

        {{-- Chi tiết đơn hàng --}}
        <div class="text-left border-t border-b py-6 my-6">
            <h3 class="font-semibold text-lg mb-4">Chi tiết đơn hàng</h3>

            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4">
                        @if($item->product_image)
                            <img src="{{ asset('storage/' . $item->product_image) }}"
                                 alt="{{ $item->product_name }}"
                                 class="w-16 h-16 object-cover rounded border">
                        @endif
                        <div class="flex-1">
                            <p class="font-medium">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ number_format($item->price, 0, ',', '.') }}đ x {{ $item->quantity }}
                            </p>
                        </div>
                        <span class="font-medium">{{ number_format($item->subtotal, 0, ',', '.') }}đ</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t mt-4 pt-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                </div>
                <div class="flex justify-between font-bold text-lg pt-2 border-t">
                    <span>Tổng thanh toán:</span>
                    <span class="text-primary">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        {{-- Thông tin giao hàng --}}
        <div class="grid md:grid-cols-2 gap-6 text-left mb-8">
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Thông tin giao hàng</h4>
                <div class="text-sm space-y-1">
                    <p><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Phương thức thanh toán</h4>
                <div class="text-sm">
                    <p>
                        @if($order->payment_method === 'cod')
                            Thanh toán khi nhận hàng (COD)
                        @else
                            Chuyển khoản ngân hàng
                        @endif
                    </p>
                    <p class="text-gray-500 mt-1">
                        Trạng thái:
                        <span class="@if($order->payment_status === 'paid') text-green-600 @else text-yellow-600 @endif">
                            {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="text-left mb-8 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-1">Ghi chú</h4>
                <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
            </div>
        @endif

        {{-- Nút điều hướng --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('account.orders.show', $order->order_number) }}"
               class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition-colors font-medium">
                Xem đơn hàng
            </a>
            <a href="{{ route('home') }}"
               class="border border-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-50 transition-colors font-medium">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>
@endsection