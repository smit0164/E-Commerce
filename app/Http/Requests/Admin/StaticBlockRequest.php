<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StaticBlock;
class StaticBlockRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $staticBlockId = $this->route('slug') 
        ?StaticBlock::where('slug', $this->route('slug'))->value('id') 
        : null;
        return [
            'title' => 'required|string|max:255|unique:static_blocks,title,'. $staticBlockId,
            'slug' => 'required|string|max:255|unique:static_blocks,slug,'. $staticBlockId,
            'content' => $this->isMethod('put')
            ?'nullable':
            'required|string',
            'is_active' => 'nullable|in:active,inactive',
        ];
    }
}
