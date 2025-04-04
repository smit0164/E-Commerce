<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change if authorization is needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $adminId = $this->route('id'); // Get the admin ID for update requests
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins', 'email')->ignore($adminId), // Ignore unique check for the current admin when updating
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable', // Required only for storing new admins
                'min:6',
                $this->isMethod('post')?'confirmed':'',
            ],
            'role_id' => 'required|exists:roles,id'
        ];
    }

    /**
     * Customize validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role_id.required' => 'Please select a role.',
            'role_id.exists' => 'The selected role is invalid.',
        ];
    }
}
