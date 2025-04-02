<?php

// app/Models/StaticPage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaticPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status', // Changed from 'is_active' to 'status'
    ];

    protected $dates = ['deleted_at'];
}