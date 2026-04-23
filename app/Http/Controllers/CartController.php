<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng
     */
 public function index(CartService $cart): View
{
    $cartItems = $cart->get();
    $subtotal  = $cart->subtotal();
    $shipping  = $subtotal >= 500000 ? 0 : 30000;
    $total     = $subtotal + $shipping;
    return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
}

    /**
     * Thêm sản phẩm vào giỏ (AJAX)
     */
    public function add(Request $request, CartService $cart): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);
        $result  = $cart->add($product, $request->quantity);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Cập nhật số lượng sản phẩm (AJAX)
     */
    public function update(Request $request, int $productId, CartService $cart): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $result = $cart->update($productId, $request->quantity);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Xóa sản phẩm khỏi giỏ (AJAX)
     */
    public function remove(int $productId, CartService $cart): JsonResponse
    {
        $cart->remove($productId);

        return response()->json([
            'success' => true,
            'count'   => $cart->count(),
        ]);
    }

    /**
     * Xóa toàn bộ giỏ hàng (AJAX)
     */
    public function clear(CartService $cart): JsonResponse
    {
        $cart->clear();

        return response()->json([
            'success' => true,
            'count'   => 0,
        ]);
    }
}