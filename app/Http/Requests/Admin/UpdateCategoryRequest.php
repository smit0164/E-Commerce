<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow request procawdessing
    }

    public function rules(): array
    {
        $categoryId = $this->route('slug') ? Category::where('slug', $this->route('slug'))->value('id') : null;

        return [
            'name' => 'required|string|min:3|unique:categories,name,' . $categoryId,
            'slug' => 'required|string|min:3|unique:categories,slug,' . $categoryId,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable for updates
            'status' => 'required|in:active,inactive',
        ];
    }
}
