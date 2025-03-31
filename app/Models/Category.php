<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\StoragePaths;
class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'image','status'];
    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }
    public function getCategoryImageUrl()
    {
        return asset('storage/' . StoragePaths::CATEGORY_IMAGE_PATH . $this->image);
    }
}