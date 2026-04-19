@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Thống kê 4 card --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Doanh thu --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tổng doanh thu</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}đ</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Tháng này: {{ number_format($monthRevenue, 0, ',', '.') }}đ</p>
        </div>

        {{-- Đơn hàng --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tổng đơn hàng</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['orders_count'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Hôm nay: {{ $todayOrders }} đơn</p>
        </div>

        {{-- Sản phẩm --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Sản phẩm đang bán</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['products_count'] ?? 0 }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-xl">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Khách hàng --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Khách hàng</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['users_count'] ?? 0 }}</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-xl">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ và Trạng thái --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Biểu đồ doanh thu 7 ngày --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Doanh thu 7 ngày qua</h3>
                <span class="text-xs text-gray-400">Đơn vị: VNĐ</span>
            </div>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Đơn hàng theo trạng thái --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Đơn hàng theo trạng thái</h3>
            <div class="space-y-3">
                @php
                    $statusLabels = [
                        'pending' => 'Chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'processing' => 'Đang xử lý',
                        'shipped' => 'Đang giao',
                        'delivered' => 'Đã giao',
                        'cancelled' => 'Đã hủy',
                    ];
                    $statusColors = [
                        'pending' => 'yellow',
                        'confirmed' => 'blue',
                        'processing' => 'purple',
                        'shipped' => 'indigo',
                        'delivered' => 'green',
                        'cancelled' => 'red',
                    ];
                @endphp
                @foreach($statusLabels as $key => $label)
                    <div class="flex items-center justify-between py-1">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-{{ $statusColors[$key] }}-500 mr-3"></span>
                            <span class="text-sm text-gray-600">{{ $label }}</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $ordersByStatus[$key] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bảng: Đơn hàng gần đây và Sản phẩm sắp hết --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Đơn hàng gần đây --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Đơn hàng gần đây</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-blue-600 hover:underline">Xem tất cả →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-100 text-gray-500 text-xs">
                        <tr>
                            <th class="pb-2 text-left font-medium">Mã ĐH</th>
                            <th class="pb-2 text-left font-medium">Khách hàng</th>
                            <th class="pb-2 text-right font-medium">Tổng tiền</th>
                            <th class="pb-2 text-left font-medium">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 font-mono text-xs">{{ $order->order_number }}</td>
                            <td class="py-3">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="py-3 text-right font-medium">{{ $order->formatted_total }}</td>
                            <td class="py-3">
                                <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">Chưa có đơn hàng nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sản phẩm sắp hết hàng --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Sản phẩm sắp hết</h3>
                <a href="{{ route('admin.products.index', ['status' => 'low_stock']) }}" class="text-xs text-blue-600 hover:underline">Xem tất cả →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-100 text-gray-500 text-xs">
                        <tr>
                            <th class="pb-2 text-left font-medium">Sản phẩm</th>
                            <th class="pb-2 text-right font-medium">Tồn kho</th>
                            <th class="pb-2 text-right font-medium">Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $product)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</td>
                            <td class="py-3 text-right">
                                <span class="font-medium @if($product->stock == 0) text-red-600 @else text-amber-600 @endif">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="py-3 text-right">{{ $product->formatted_display_price }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-400">Không có sản phẩm sắp hết.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        
        let existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData->pluck('date')),
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: @json($chartData->pluck('revenue')),
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => new Intl.NumberFormat('vi-VN').format(context.raw) + 'đ'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => new Intl.NumberFormat('vi-VN').format(value) + 'đ'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush