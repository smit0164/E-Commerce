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
}
