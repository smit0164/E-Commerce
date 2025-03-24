<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id',
        'type',             // Add this to allow mass assignment of 'shipping' or 'billing'
        'full_name',        // Replace 'customer_name' to match your service
        'phone',            // Replace 'customer_phone' to match your service
        'address_line1',    // Replace 'address_line' to match your service and schema
        'address_line2',    // Add this (nullable in schema)
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
        // 'customer_email' removed unless you plan to add it to the schema
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