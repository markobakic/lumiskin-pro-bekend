<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** Statusi porudžbine redom kojim porudžbina prolazi. */
    public const STATUSES = ['new', 'confirmed', 'shipped', 'delivered', 'cancelled'];

    protected $fillable = [
        'number', 'status',
        'first_name', 'last_name', 'email', 'phone', 'address', 'postal_code', 'city', 'note',
        'subtotal', 'discount', 'total', 'discount_code', 'discount_percent',
        'payment_method',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
        'discount_percent' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function formatNumber(int $id): string
    {
        return 'LSP-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }
}
