@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h1>

    @if(empty($cartItems))
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-gray-500 text-lg mt-4">Giỏ hàng trống.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition-colors">
                Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Bảng giỏ hàng --}}
            <div class="lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-left text-gray-600 text-sm">
                                <th class="px-6 py-4">Sản phẩm</th>
                                <th class="px-6 py-4 text-right">Đơn giá</th>
                                <th class="px-6 py-4 text-center">Số lượng</th>
                                <th class="px-6 py-4 text-right">Thành tiền</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50" 
                                    x-data="cartItem({{ $item['product_id'] }}, {{ $item['quantity'] }}, {{ $item['price'] }}, {{ $item['max_stock'] }})">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                            <div>
                                                <a href="{{ route('products.show', $item['slug']) }}"
                                                   class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                                    {{ $item['name'] }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{ number_format($item['price'], 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center">
                                            <button @click="decrement()" class="w-8 h-8 border border-gray-200 rounded-l-lg hover:bg-gray-100">−</button>
                                            <input type="number" x-model="quantity" @change="updateQuantity()"
                                                   min="1" :max="maxStock"
                                                   class="w-14 h-8 border-y border-gray-200 text-center text-sm focus:outline-none">
                                            <button @click="increment()" class="w-8 h-8 border border-gray-200 rounded-r-lg hover:bg-gray-100">+</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium" x-text="formatMoney(subtotal)">
                                        {{ number_format($item['subtotal'], 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="removeItem()" class="text-red-500 hover:text-red-700 transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">
                        ← Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            {{-- Tổng kết giỏ hàng --}}
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-lg mb-4">Tổng giỏ hàng</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính</span>
                            <span class="font-medium">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển</span>
                            <span>
                                @if($subtotal >= 500000)
                                    <span class="text-green-600 font-medium">Miễn phí</span>
                                @else
                                    {{ number_format($shipping, 0, ',', '.') }}đ
                                @endif
                            </span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Tổng cộng</span>
                                <span class="text-blue-600">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium text-center py-3 rounded-lg mt-6 transition-colors shadow-sm">
                        Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function cartItem(productId, initialQty, price, maxStock) {
        return {
            productId: productId,
            quantity: initialQty,
            price: price,
            maxStock: maxStock,
            subtotal: initialQty * price,

            formatMoney(amount) {
                return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
            },

            async updateQuantity() {
                if (this.quantity < 1) this.quantity = 1;
                if (this.quantity > this.maxStock) this.quantity = this.maxStock;

                const res = await fetch(`/cart/${this.productId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quantity: this.quantity })
                });

                const data = await res.json();
                if (data.success) {
                    this.subtotal = this.quantity * this.price;
                    this.updateCartCount(data.count);
                    location.reload();
                } else {
                    alert(data.message);
                    location.reload();
                }
            },

            increment() {
                if (this.quantity < this.maxStock) {
                    this.quantity++;
                    this.updateQuantity();
                }
            },

            decrement() {
                if (this.quantity > 1) {
                    this.quantity--;
                    this.updateQuantity();
                }
            },

            async removeItem() {
                if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

                const res = await fetch(`/cart/${this.productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });

                const data = await res.json();
                if (data.success) {
                    this.updateCartCount(data.count);
                    location.reload();
                }
            },

            updateCartCount(count) {
                document.querySelectorAll('.cart-count').forEach(el => {
                    el.textContent = count;
                });
            }
        };
    }
</script>
@endpush