<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'product_name',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Optional: If you have a Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}