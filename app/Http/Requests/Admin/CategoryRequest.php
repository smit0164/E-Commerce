<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the category ID if updating
        $categoryId = $this->route('slug') 
            ? Category::where('slug', $this->route('slug'))->value('id') 
            : null;

        return [
            'name' => 'required|string|min:3|max:255|unique:categories,name,id' . $categoryId,
            'slug' => 'required|string|min:3|max:255|unique:categories,slug,' . $categoryId,
            'image' => $this->isMethod('post') 
                ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048'  // Required for creation
                : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Nullable for updates
            'status' => 'required|in:active,inactive',
        ];
    }
}
