<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng của khách hàng
     */
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết một đơn hàng
     */
    public function show(string $orderNumber): View
    {
        $order = auth()->user()
            ->orders()
            ->where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        return view('account.orders.show', compact('order'));
    }

    /**
     * Hủy đơn hàng (nếu được phép)
     */
    public function cancel(CancelOrderRequest $request, string $orderNumber, OrderService $orderService): RedirectResponse
    {
        $order = auth()->user()
            ->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (!$order->canBeCancelledByCustomer()) {
            return back()->with('error', 'Đơn hàng này không thể hủy.');
        }

        // Kiểm tra nếu đã thanh toán thì không cho hủy (có thể yêu cầu liên hệ admin)
        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Đơn hàng đã thanh toán không thể tự hủy. Vui lòng liên hệ admin.');
        }

        $orderService->cancelOrder($order, $request->cancel_reason);

        return back()->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    /**
     * Đánh dấu đơn hàng đã thanh toán (Demo cho khách hàng)
     */
    public function markAsPaid(string $orderNumber): RedirectResponse
    {
        $order = auth()->user()
            ->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Đơn hàng đã được thanh toán rồi.');
        }

        $order->update(['payment_status' => 'paid']);

        return back()->with('success', 'Cảm ơn bạn! Đơn hàng đã được đánh dấu thanh toán (Demo).');
    }
}