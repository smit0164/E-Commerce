<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'payment_method' => 'required|in:cod',
        ];

        $shippingId = $this->input('shipping_address_id');
        if (!isset($shippingId) || $shippingId === 'new') {
            $rules['shipping.address_line1'] = 'required|string|max:255';
            $rules['shipping.city'] = 'required|string|max:100';
            $rules['shipping.state'] = 'required|string|max:100';
            $rules['shipping.postal_code'] = 'required|digits_between:4,10';
            $rules['shipping.country'] = 'required|string|max:100';
        }

        $billingId = $this->input('billing_address_id');
        if (!isset($billingId) || $billingId === 'new') {
            $rules['billing.address_line1'] = 'required|string|max:255';
            $rules['billing.city'] = 'required|string|max:100';
            $rules['billing.state'] = 'required|string|max:100';
            $rules['billing.postal_code'] = 'required|digits_between:4,10';
            $rules['billing.country'] = 'required|string|max:100';
        }

        return $rules;
    }

    /**
     * Customize the validation messages (optional).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name.',
            'email.required' => 'An email address is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'payment_method.required' => 'Please select a payment method.',
            // Add more custom messages as needed
        ];
    }
}