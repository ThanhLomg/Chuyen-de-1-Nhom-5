<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng với bộ lọc
     */
    public function index(Request $request): View
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(20)->withQueryString();
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $allStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'statusCounts', 'allStatuses'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show(int $id): View
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        $allowedTransitions = Order::allowedTransitions()[$order->status] ?? [];

        return view('admin.orders.show', compact('order', 'allowedTransitions'));
    }

    /**
     * Cập nhật trạng thái đơn hàng (AJAX)
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $allowed = Order::allowedTransitions()[$order->status] ?? [];

        if (!in_array($request->status, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chuyển sang trạng thái này.',
            ], 422);
        }

        $timestamps = [
            'confirmed' => 'confirmed_at',
            'shipped'   => 'shipped_at',
            'delivered' => 'delivered_at',
            'cancelled' => 'cancelled_at',
        ];

        $updateData = ['status' => $request->status];
        if (isset($timestamps[$request->status])) {
            $updateData[$timestamps[$request->status]] = now();
        }

        // Nếu chuyển sang cancelled, hoàn lại tồn kho
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            DB::transaction(function () use ($order, $updateData) {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->quantity);
                }
                $order->update($updateData);
            });
        } else {
            $order->update($updateData);
        }

        $order->refresh();

        return response()->json([
            'success'    => true,
            'new_status' => $order->status,
            'label'      => $order->status_label,
            'color'      => $order->status_color,
        ]);
    }

    /**
     * Đánh dấu đơn hàng đã thanh toán (Demo)
     */
    public function markAsPaid(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Đơn hàng đã được thanh toán rồi.');
        }

        $order->update(['payment_status' => 'paid']);

        return back()->with('success', 'Đã đánh dấu thanh toán thành công (Demo).');
    }
}