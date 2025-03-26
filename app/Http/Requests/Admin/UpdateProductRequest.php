<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ensure authorized users can perform this action
    }

    public function rules()
    {
        $productId = $this->route('slug') ? \App\Models\Product::where('slug', $this->route('slug'))->value('id') : null;

        return [
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'nullable|boolean',
            'description' => 'nullable|string',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}
