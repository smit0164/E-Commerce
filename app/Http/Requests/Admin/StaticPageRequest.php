<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\StaticPage;

class StaticPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust based on your authorization logic (e.g., auth()->user()->can('manage-static-pages'))
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Get the static page ID from the route (for update) or null (for store)
        $staticPageId = $this->route('slug') ? StaticPage::where('slug', $this->route('slug'))->firstOrFail()->id : null;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('static_pages', 'title')->ignore($staticPageId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('static_pages', 'slug')->ignore($staticPageId),
            ],
            'content' => 'required',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
   
}