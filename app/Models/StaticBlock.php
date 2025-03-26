<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticBlock extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'is_active'];
    
}
