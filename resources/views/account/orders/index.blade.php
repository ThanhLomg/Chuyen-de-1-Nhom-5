@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <p class="text-gray-500 text-lg mt-4">Bạn chưa có đơn hàng nào.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-6 bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                Mua sắm ngay
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="px-4 py-3">Mã đơn hàng</th>
                        <th class="px-4 py-3">Ngày đặt</th>
                        <th class="px-4 py-3">Số sản phẩm</th>
                        <th class="px-4 py-3">Tổng tiền</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-4 font-mono text-sm">{{ $order->order_number }}</td>
                            <td class="px-4 py-4">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-4">{{ $order->items->sum('quantity') }}</td>
                            <td class="px-4 py-4 font-medium">{{ $order->formatted_total }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('account.orders.show', $order->order_number) }}"
                                   class="text-primary hover:underline">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection