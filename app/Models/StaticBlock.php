<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class StaticBlock extends Model
{   
    use SoftDeletes;
    protected $fillable = ['title', 'slug', 'content', 'is_active'];
    protected $dates = ['deleted_at'];
}
