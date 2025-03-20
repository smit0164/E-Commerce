<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['email', 'password', 'status', 'role_id'];
    
    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
