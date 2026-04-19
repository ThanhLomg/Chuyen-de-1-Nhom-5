@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-primary">Đơn hàng</a> /
    <span>{{ $order->order_number }}</span>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Cột trái: Sản phẩm và tổng --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-lg mb-4">Sản phẩm đặt mua</h3>
            <table class="w-full text-sm">
                <thead class="border-b text-gray-600">
                    <tr>
                        <th class="pb-2 text-left">Sản phẩm</th>
                        <th class="pb-2 text-right">Đơn giá</th>
                        <th class="pb-2 text-center">SL</th>
                        <th class="pb-2 text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b last:border-0">
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                @if($item->product_image)
                                <img src="{{ asset('storage/' . $item->product_image) }}" class="w-10 h-10 object-cover rounded border">
                                @endif
                                <span>{{ $item->product_name }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                        <td class="py-3 text-center">{{ $item->quantity }}</td>
                        <td class="py-3 text-right font-medium">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t mt-4 pt-4 text-right space-y-1">
                <div class="flex justify-end gap-8">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                </div>
                <div class="flex justify-end gap-8">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-end gap-8">
                    <span class="text-gray-600">Giảm giá:</span>
                    <span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                </div>
                @endif
                <div class="flex justify-end gap-8 font-bold text-lg pt-2 border-t">
                    <span>Tổng cộng:</span>
                    <span class="text-primary">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Cột phải: Thông tin đơn hàng, giao hàng, thanh toán --}}
    <div class="space-y-6">
        {{-- Thông tin đơn hàng & Cập nhật trạng thái --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-lg mb-4">Thông tin đơn hàng</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Mã đơn:</dt>
                    <dd class="font-mono">{{ $order->order_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Ngày đặt:</dt>
                    <dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Khách hàng:</dt>
                    <dd>{{ $order->user->name ?? $order->shipping_name }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-600">Trạng thái:</dt>
                    <dd>
                        <span id="status-badge" class="px-2 py-1 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                            {{ $order->status_label }}
                        </span>
                    </dd>
                </div>
            </dl>

            @if(count($allowedTransitions) > 0)
            <div class="mt-4 pt-4 border-t">
                <label for="status-select" class="block text-sm font-medium mb-2">Cập nhật trạng thái</label>
                <select id="status-select" class="w-full border-gray-300 rounded-md text-sm">
                    @foreach($allowedTransitions as $status)
                        @php
                            $labels = [
                                'pending' => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'processing' => 'Đang xử lý',
                                'shipped' => 'Đang giao hàng',
                                'delivered' => 'Đã giao hàng',
                                'cancelled' => 'Đã hủy',
                            ];
                        @endphp
                        <option value="{{ $status }}">{{ $labels[$status] }}</option>
                    @endforeach
                </select>
                <button onclick="updateOrderStatus({{ $order->id }})"
                        class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition-colors">
                    Cập nhật
                </button>
            </div>
            @endif
        </div>

        {{-- Thông tin giao hàng --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-lg mb-4">Thông tin giao hàng</h3>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="text-gray-600">Người nhận:</dt>
                    <dd class="font-medium">{{ $order->shipping_name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600">SĐT:</dt>
                    <dd>{{ $order->shipping_phone }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600">Địa chỉ:</dt>
                    <dd>{{ $order->shipping_address }}, {{ $order->shipping_city }}</dd>
                </div>
            </dl>
        </div>

        {{-- Thanh toán --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-lg mb-4">Thanh toán</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Phương thức:</dt>
                    <dd>{{ $order->payment_method === 'cod' ? 'COD' : 'Chuyển khoản' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Trạng thái:</dt>
                    <dd class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </dd>
                </div>
            </dl>

            @if($order->payment_status === 'unpaid')
                <form action="{{ route('admin.orders.markPaid', $order->id) }}" method="POST" class="mt-4">
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

        {{-- Lý do hủy (nếu đã hủy) --}}
        @if($order->status === 'cancelled')
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <h3 class="font-semibold text-lg mb-2 text-red-600">Đã hủy đơn hàng</h3>
            <p class="text-gray-700"><strong>Lý do:</strong> {{ $order->cancel_reason }}</p>
            <p class="text-xs text-gray-500 mt-2">Hủy lúc: {{ $order->cancelled_at?->format('d/m/Y H:i') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function updateOrderStatus(orderId) {
        const select = document.getElementById('status-select');
        const newStatus = select.value;
        const badge = document.getElementById('status-badge');

        if (!confirm('Bạn có chắc muốn chuyển trạng thái đơn hàng?')) return;

        try {
            const res = await fetch(`/admin/orders/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });

            const data = await res.json();

            if (data.success) {
                badge.textContent = data.label;
                badge.className = `px-2 py-1 text-xs rounded-full bg-${data.color}-100 text-${data.color}-800`;
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            alert('Lỗi kết nối, vui lòng thử lại.');
        }
    }
</script>
@endpush