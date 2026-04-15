<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Lấy toàn bộ giỏ hàng từ session
     */
    public function get(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Tổng số lượng sản phẩm trong giỏ
     */
    public function count(): int
    {
        return array_sum(array_column($this->get(), 'quantity'));
    }

    /**
     * Tổng tiền tạm tính (chưa bao gồm phí vận chuyển)
     */
    public function total(): int
    {
        return array_sum(array_column($this->get(), 'subtotal'));
    }

    /**
     * Alias cho total()
     */
    public function subtotal(): int
    {
        return $this->total();
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Product $product, int $quantity = 1): array
    {
        if (!$product->is_active || $product->stock === 0) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không còn hàng.',
                'count'   => $this->count(),
            ];
        }

        $cart = $this->get();
        $existingQty = $cart[$product->id]['quantity'] ?? 0;
        $maxAllowed = min($product->stock, 99);
        $newQty = min($existingQty + $quantity, $maxAllowed);

        // Nếu đã đạt giới hạn tối đa
        if ($newQty === $existingQty && $newQty === $maxAllowed) {
            return [
                'success' => true,
                'message' => "Chỉ còn {$product->stock} sản phẩm trong kho.",
                'count'   => $this->count(),
            ];
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name'       => $product->name,
            'slug'       => $product->slug,
            'price'      => $product->display_price,
            'image_url'  => $product->image_url,
            'quantity'   => $newQty,
            'subtotal'   => $product->display_price * $newQty,
            'max_stock'  => $product->stock,
        ];

        Session::put(self::SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
            'count'   => $this->count(),
        ];
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(int $productId, int $quantity): array
    {
        $cart = $this->get();

        if (!isset($cart[$productId])) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ',
                'count'   => $this->count(),
            ];
        }

        if ($quantity <= 0) {
            $this->remove($productId);
            return [
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ',
                'count'   => $this->count(),
            ];
        }

        $product = Product::find($productId);
        if (!$product || !$product->is_active) {
            $this->remove($productId);
            return [
                'success' => false,
                'message' => 'Sản phẩm không còn khả dụng',
                'count'   => $this->count(),
            ];
        }

        $maxQty = min($product->stock, 99);
        if ($quantity > $maxQty) {
            $quantity = $maxQty;
        }

        $cart[$productId]['quantity'] = $quantity;
        $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $quantity;
        Session::put(self::SESSION_KEY, $cart);

        $message = ($quantity === $maxQty && $maxQty === $product->stock)
            ? "Chỉ còn {$maxQty} sản phẩm trong kho."
            : 'Đã cập nhật giỏ hàng';

        return [
            'success' => true,
            'message' => $message,
            'count'   => $this->count(),
        ];
    }

    /**
     * Xóa một sản phẩm khỏi giỏ
     */
    public function remove(int $productId): void
    {
        $cart = $this->get();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Kiểm tra giỏ hàng có trống không
     */
    public function isEmpty(): bool
    {
        return empty($this->get());
    }

    /**
     * Kiểm tra sản phẩm có trong giỏ không
     */
    public function hasProduct(int $productId): bool
    {
        return isset($this->get()[$productId]);
    }
}