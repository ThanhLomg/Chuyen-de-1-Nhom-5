<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();

        // Thống kê tổng quan - doanh thu tính theo đơn đã thanh toán (paid)
        $stats = [
            'revenue'       => Order::where('payment_status', 'paid')->sum('total'),
            'orders_count'  => Order::count(),
            'products_count'=> Product::active()->count(),
            'users_count'   => User::customers()->count(),
        ];

        // Đếm đơn hàng theo trạng thái
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Doanh thu 7 ngày gần nhất (chỉ tính đơn đã thanh toán)
        $startDate = $now->copy()->subDays(6)->startOfDay();
        $endDate   = $now->copy()->endOfDay();

        $dailyRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->pluck('revenue', 'date')
            ->toArray();

        $chartData = collect(range(6, 0))->map(function ($daysAgo) use ($now, $dailyRevenue) {
            $date = $now->copy()->subDays($daysAgo);
            return [
                'date'    => $date->format('d/m'),
                'revenue' => (int) ($dailyRevenue[$date->format('Y-m-d')] ?? 0),
            ];
        });

        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Sản phẩm sắp hết hàng (tồn kho từ 1 đến 5)
        $lowStockItems = Product::active()
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->take(10)
            ->get();

        // Doanh thu tháng này (chỉ tính đơn đã thanh toán)
        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');

        // Đơn hàng hôm nay
        $todayOrders = Order::whereDate('created_at', $now->toDateString())->count();

        return view('admin.dashboard', compact(
            'stats',
            'ordersByStatus',
            'chartData',
            'recentOrders',
            'lowStockItems',
            'monthRevenue',
            'todayOrders'
        ));
    }
}