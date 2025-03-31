<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\StoragePaths;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'status', 'quantity', 'image'
    ];
    protected $dates = ['deleted_at'];
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }
    public function getProductImageUrl()
    {
        return asset('storage/' . StoragePaths::PRODUCT_IMAGE_PATH. $this->image);
    }
}

