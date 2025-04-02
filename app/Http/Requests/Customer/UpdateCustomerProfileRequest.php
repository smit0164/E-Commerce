<?php
namespace App\Http\Requests\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
   
    public function authorize()
    {
        // Only allow the authenticated customer to update their own profile
        return true;
    }
    
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $customerId = Auth::guard('customer')->user()->id; // Get the currently authenticated customer ID
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|max:255|email|unique:customers,email,' . $customerId,
            'phone' => 'nullable|string|max:15',
        ];
    }
}
