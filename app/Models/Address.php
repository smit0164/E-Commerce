<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id',      
        'customer_name',
        'customer_phone',
        'customer_email',
        'address_line',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}