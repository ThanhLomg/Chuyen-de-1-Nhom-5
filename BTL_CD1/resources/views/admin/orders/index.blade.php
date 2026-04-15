@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <span>Đơn hàng</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">Danh sách đơn hàng</h2>
    </div>

    {{-- Tabs trạng thái --}}
    <div class="px-6 pt-4 flex flex-wrap gap-2 border-b pb-4">
        <a href="{{ route('admin.orders.index') }}"
           class="px-4 py-2 rounded-full text-sm font-medium {{ !request('status') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Tất cả ({{ $orders->total() }})
        </a>
        @foreach($allStatuses as $status)
            @php
                $labels = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'processing' => 'Đang xử lý',
                    'shipped' => 'Đang giao',
                    'delivered' => 'Đã giao',
                    'cancelled' => 'Đã hủy',
                ];
            @endphp
            <a href="{{ route('admin.orders.index', ['status' => $status]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ request('status') == $status ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $labels[$status] }} ({{ $statusCounts[$status] ?? 0 }})
            </a>
        @endforeach
    </div>

    {{-- Form lọc --}}
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Từ ngày</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Đến ngày</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="flex-1 min-w-[250px]">
                <label class="block text-sm text-gray-600 mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Mã đơn, tên khách, email..."
                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark text-sm">
                Lọc
            </button>
            <a href="{{ route('admin.orders.index') }}"
               class="border border-gray-300 bg-white px-4 py-2 rounded-md hover:bg-gray-50 text-sm">
                Reset
            </a>
        </form>
    </div>

    {{-- Bảng danh sách --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-gray-600">
                    <th class="px-6 py-3">Mã đơn hàng</th>
                    <th class="px-6 py-3">Khách hàng</th>
                    <th class="px-6 py-3">Ngày đặt</th>
                    <th class="px-6 py-3">Tổng tiền</th>
                    <th class="px-6 py-3">Thanh toán</th>
                    <th class="px-6 py-3">Trạng thái</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono">{{ $order->order_number }}</td>
                    <td class="px-6 py-4">
                        <div>{{ $order->shipping_name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 font-medium">{{ $order->formatted_total }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->payment_status === 'paid' ? 'Đã TT' : 'Chưa TT' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="text-primary hover:underline">Chi tiết</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">Không có đơn hàng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t">
        {{ $orders->links() }}
    </div>
</div>
@endsection