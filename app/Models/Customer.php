<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'phone', 'status'];

    protected $hidden = ['password'];
    public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }
    public function orders(){
        return $this->hasMany(Order::class,'customer_id');
    }
}
