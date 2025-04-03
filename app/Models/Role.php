<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'is_super_admin'];
    protected $dates = ['deleted_at'];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function permissions()
    {
        Log::info('Accessing permissions relationship for model ID: ' . $this->id);
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}