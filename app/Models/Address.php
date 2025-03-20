<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'name', 'customer_name', 'customer_phone', 'address_line', 'city', 'state', 'postal_code', 'country',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}