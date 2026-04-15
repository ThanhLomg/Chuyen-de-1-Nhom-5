<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'shipping_fee',
        'discount',
        'total',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'payment_method',
        'payment_status',
        'notes',
        'cancel_reason',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'shipped_at'   => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing'=> 'Đang xử lý',
            'shipped'   => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'processing'=> 'purple',
            'shipped'   => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', '.') . 'đ';
    }

    public function canBeCancelledByCustomer(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public static function allowedTransitions(): array
    {
        return [
            'pending'   => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing'=> ['shipped', 'cancelled'],
            'shipped'   => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ];
    }
}