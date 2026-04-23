<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang checkout
     */
    public function index(CartService $cart): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

            $cartItems = $cart->get();
            $subtotal  = $cart->subtotal();
            $shipping  = $subtotal >= 500000 ? 0 : 30000;
            $total     = $subtotal + $shipping;
            $user      = auth()->user();
    return view('checkout.index', compact('cartItems','subtotal','shipping','total','user'));

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total', 'user'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(CheckoutRequest $request, CartService $cart, OrderService $orderService): RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống.');
        }

        $order = $orderService->createFromCart(
            auth()->user(),
            $cart->get(),
            $request->validated()
        );

        return redirect()->route('checkout.success', $order->order_number);
    }

    /**
     * Hiển thị trang đặt hàng thành công
     */
    public function success(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with('items')
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}