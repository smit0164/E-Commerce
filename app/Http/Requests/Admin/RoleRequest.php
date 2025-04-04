<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic (e.g., check if user is admin)
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roleId = $this->route('id'); // Ensure correct route parameter
        
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique('roles')->ignore($roleId),
            ],
            'is_super_admin' => 'required|in:yes,no', // Ensure it only accepts 'yes' or 'no'
            'permissions' => $this->input('is_super_admin') === 'yes' 
                ? 'nullable' // Super Admin doesn't need permissions
                : 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required.',
            'name.min' => 'The role name must be at least 2 characters.',
            'name.max' => 'The role name may not be greater than 50 characters.',
            'name.unique' => 'This role name is already taken.',
            'permissions.required' => 'Please select at least one permission.',
            'permissions.min' => 'Please select at least one permission.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
            'is_super_admin.in' => 'Invalid value for super admin status.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_super_admin' => $this->input('is_super_admin') === 'yes' ? 'yes' : 'no',
        ]);
    }
    
}
