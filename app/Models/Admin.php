<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','email', 'password', 'status', 'role_id'];
    
    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
    public function hasPermission($permission) {
        $hasPermission = $this->role()->whereHas("permissions", function ($query) use ($permission) {
              $query->where("slug", $permission);
        })->exists();
        return $hasPermission;
    }
    public function isSuperAdmin() {
        return $this->role && $this->role->is_super_admin === "yes";
    }
    
}
