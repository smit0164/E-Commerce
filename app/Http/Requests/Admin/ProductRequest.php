<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ensure only authorized users can make this request
    }

    public function rules()
    {

        $productId = null;

        if ($this->route('slug')) {
            $productId = Product::where('slug', $this->route('slug'))->value('id');
        }
       
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'exists:categories,id',
            'image' => ($this->isMethod('post') ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}

