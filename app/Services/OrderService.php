<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Tạo mã đơn hàng duy nhất
     */
    public function generateOrderNumber(): string
    {
        $attempts = 0;
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $attempts++;
        } while (Order::where('order_number', $number)->exists() && $attempts < 5);

        return $number;
    }

    /**
     * Tạo đơn hàng từ giỏ hàng
     * Miễn phí vận chuyển nếu tổng tiền >= 500.000 VNĐ
     */
    public function createFromCart(User $user, array $cartItems, array $shippingData): Order
    {
        return DB::transaction(function () use ($user, $cartItems, $shippingData) {
            $subtotal = array_sum(array_column($cartItems, 'subtotal'));
            
            // Miễn phí vận chuyển cho đơn từ 500.000đ
            $shippingFee = $subtotal >= 500000 ? 0 : 30000;
            $total = $subtotal + $shippingFee;

            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => $this->generateOrderNumber(),
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'discount'         => 0,
                'total'            => $total,
                'shipping_name'    => $shippingData['shipping_name'],
                'shipping_phone'   => $shippingData['shipping_phone'],
                'shipping_address' => $shippingData['shipping_address'],
                'shipping_city'    => $shippingData['shipping_city'],
                'payment_method'   => $shippingData['payment_method'],
                'payment_status'   => 'unpaid',
                'notes'            => $shippingData['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'product_image' => $product->image,
                    'price'         => $item['price'],
                    'quantity'      => $item['quantity'],
                    'subtotal'      => $item['subtotal'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            app(CartService::class)->clear();

            return $order;
        });
    }

    /**
     * Hủy đơn hàng
     */
    public function cancelOrder(Order $order, string $reason, bool $byAdmin = false): bool
    {
        if (!$byAdmin && !$order->canBeCancelledByCustomer()) {
            return false;
        }

        if (!$byAdmin && $order->payment_status === 'paid') {
            return false;
        }

        DB::transaction(function () use ($order, $reason) {
            $order->update([
                'status'        => 'cancelled',
                'cancel_reason' => $reason,
                'cancelled_at'  => now(),
            ]);

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
        });

        return true;
    }
}