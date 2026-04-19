@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Đơn hàng #{{ $order->order_number }}</h1>
            <p class="text-gray-500 text-sm mt-1">
                Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-block px-3 py-1 text-sm rounded-full
                bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Danh sách sản phẩm --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-lg mb-4">Sản phẩm đã đặt</h3>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-3 border-b last:border-0">
                            @if($item->product_image)
                                <img src="{{ asset('storage/' . $item->product_image) }}"
                                     alt="{{ $item->product_name }}"
                                     class="w-16 h-16 object-cover rounded border">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded border flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1">
                                <a href="{{ route('products.show', $item->product->slug ?? '#') }}"
                                   class="font-medium hover:text-blue-600">
                                    {{ $item->product_name }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ $item->formatted_price }} x {{ $item->quantity }}
                                </p>
                            </div>

                            <div class="text-right">
                                <span class="font-medium">{{ $item->formatted_subtotal }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tổng kết --}}
                <div class="border-t mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính</span>
                        <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Giảm giá</span>
                            <span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Tổng cộng</span>
                        <span class="text-blue-600">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thông tin bên phải --}}
        <div class="space-y-6">
            {{-- Thông tin giao hàng --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-lg mb-4">Thông tin giao hàng</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">Người nhận</dt>
                        <dd class="font-medium">{{ $order->shipping_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Số điện thoại</dt>
                        <dd>{{ $order->shipping_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Địa chỉ</dt>
                        <dd>{{ $order->shipping_address }}, {{ $order->shipping_city }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Thông tin thanh toán --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-lg mb-4">Thanh toán</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">Phương thức</dt>
                        <dd>{{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Trạng thái</dt>
                        <dd>
                            <span class="@if($order->payment_status === 'paid') text-green-600 @else text-yellow-600 @endif">
                                {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                            </span>
                        </dd>
                    </div>
                </dl>

                {{-- Nút demo thanh toán cho khách hàng --}}
                @if($order->payment_status === 'unpaid')
                    <form action="{{ route('account.orders.markPaid', $order->order_number) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            💵 Xác nhận đã thanh toán (Demo)
                        </button>
                    </form>
                @endif
            </div>

            {{-- Ghi chú --}}
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-lg mb-2">Ghi chú</h3>
                    <p class="text-gray-700 text-sm">{{ $order->notes }}</p>
                </div>
            @endif

            {{-- Nút hủy đơn hàng --}}
            @if($order->canBeCancelledByCustomer() && $order->payment_status !== 'paid')
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-lg mb-4 text-red-600">Hủy đơn hàng</h3>

                    <form method="POST" action="{{ route('account.orders.cancel', $order->order_number) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="cancel_reason" class="block text-sm font-medium mb-1">
                                Lý do hủy <span class="text-red-500">*</span>
                            </label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="3"
                                      class="w-full border-gray-300 rounded-md @error('cancel_reason') border-red-500 @enderror"
                                      placeholder="Vui lòng cho biết lý do bạn muốn hủy đơn hàng...">{{ old('cancel_reason') }}</textarea>
                            @error('cancel_reason')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-md transition-colors"
                                onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                            Xác nhận hủy đơn hàng
                        </button>
                    </form>
                </div>
            @endif

            {{-- Thông tin hủy đơn (nếu đã hủy) --}}
            @if($order->status === 'cancelled')
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                    <h3 class="font-semibold text-lg mb-2 text-red-600">Đơn hàng đã bị hủy</h3>
                    <p class="text-sm text-gray-700"><strong>Lý do:</strong> {{ $order->cancel_reason }}</p>
                    <p class="text-xs text-gray-500 mt-2">Hủy lúc: {{ $order->cancelled_at?->format('d/m/Y H:i') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Nút quay lại --}}
    <div class="mt-6">
        <a href="{{ route('account.orders.index') }}" class="text-blue-600 hover:underline">
            ← Quay lại danh sách đơn hàng
        </a>
    </div>
</div>
@endsection