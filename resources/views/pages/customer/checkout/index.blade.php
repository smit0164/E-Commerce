@extends('layouts.users.app')

@section('content')
    <div class="w-full max-w-6xl mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Complete Your Order</h2>

        @if (empty($cartItems))
            <p class="text-gray-600 text-center">
                Your cart is empty, friend!
                <a href="{{ route('home') }}" class="text-red-600 hover:underline">Explore Our Shop</a>
            </p>
        @else
            <div class="flex flex-col lg:flex-row-reverse items-start gap-8">
                <!-- Order Summary -->
                <div class="lg:w-1/3 flex-shrink-0">
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Your Order Summary</h3>
                        <ul class="space-y-4">
                            @foreach ($cartItems as $item)
                                <li class="flex justify-between items-center bg-gray-50 p-3 rounded-md">
                                    <span class="text-gray-800 font-medium">
                                        {{ $item['name'] }} <span class="text-gray-500 text-sm">(x{{ $item['quantity'] }})</span>
                                    </span>
                                    <span class="text-gray-900 font-semibold">
                                        ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <hr class="my-5 border-gray-200">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium text-gray-700">Total:</p>
                            <p class="text-2xl font-bold text-red-600">
                                ₹{{ number_format($totalPrice, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="lg:w-2/3 flex-1">
                    <form action="{{ route('checkout.store') }}" method="POST" class="bg-white border border-gray-200 rounded-xl p-8 shadow-md max-w-4xl mx-auto" id="checkoutForm">
                        @csrf

                        <!-- Personal Details -->
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Your Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth('customer')->user()->name ?? '') }}" class="w-full border-gray-300 rounded-lg p-3" required>
                                @error('name')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', auth('customer')->user()->email ?? '') }}" class="w-full border-gray-300 rounded-lg p-3" required>
                                @error('email')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', auth('customer')->user()->phone ?? '') }}" pattern="[0-9]{10}" maxlength="10" class="w-full border-gray-300 rounded-lg p-3" required>
                                @error('phone')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Shipping Address Section -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Shipping Address</h4>
                            @if (auth('customer')->check() && auth('customer')->user()->addresses->isNotEmpty())
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select an Existing Shipping Address</label>
                                    <select name="shipping_address_id" id="shipping_address_id" class="w-full border-gray-300 rounded-lg p-3">
                                        <option value="">-- Select an Address --</option>
                                        @foreach (auth('customer')->user()->addresses->where('type', 'shipping') as $address)
                                            <option value="{{ $address->id }}" {{ old('shipping_address_id') == $address->id ? 'selected' : ($address->is_default && !old('shipping_address_id') ? 'selected' : '') }}>
                                                {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}, {{ $address->country }}
                                            </option>
                                        @endforeach
                                        <option value="new" {{ old('shipping_address_id') == 'new' ? 'selected' : '' }}>Add a New Shipping Address</option>
                                    </select>
                                    @error('shipping_address_id')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div id="newShippingAddressForm" class="{{ auth('customer')->check() && auth('customer')->user()->addresses->isNotEmpty() && old('shipping_address_id') != 'new' ? 'hidden' : '' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="shipping_address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                        <input type="text" name="shipping[address_line1]" id="shipping_address_line1" value="{{ old('shipping.address_line1') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('shipping.address_line1')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                                        <input type="text" name="shipping[address_line2]" id="shipping_address_line2" value="{{ old('shipping.address_line2') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('shipping.address_line2')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                        <input type="text" name="shipping[city]" id="shipping_city" value="{{ old('shipping.city') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('shipping.city')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                        <input type="text" name="shipping[state]" id="shipping_state" value="{{ old('shipping.state') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('shipping.state')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                        <input type="text" name="shipping[postal_code]" id="shipping_postal_code" value="{{ old('shipping.postal_code') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('shipping.postal_code')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                        <input type="text" name="shipping[country]" id="shipping_country" value="{{ old('shipping.country') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('shipping.country')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address Section -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Billing Address</h4>
                            @if (auth('customer')->check() && auth('customer')->user()->addresses->isNotEmpty())
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select an Existing Billing Address</label>
                                    <select name="billing_address_id" id="billing_address_id" class="w-full border-gray-300 rounded-lg p-3">
                                        <option value="">-- Select an Address --</option>
                                        @foreach (auth('customer')->user()->addresses->where('type', 'billing') as $address)
                                            <option value="{{ $address->id }}" {{ old('billing_address_id') == $address->id ? 'selected' : ($address->is_default && !old('billing_address_id') ? 'selected' : '') }}>
                                                {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}, {{ $address->country }}
                                            </option>
                                        @endforeach
                                        <option value="new" {{ old('billing_address_id') == 'new' ? 'selected' : '' }}>Add a New Billing Address</option>
                                    </select>
                                    @error('billing_address_id')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div id="newBillingAddressForm" class="{{ auth('customer')->check() && auth('customer')->user()->addresses->isNotEmpty() && old('billing_address_id') != 'new' ? 'hidden' : '' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="billing_address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                        <input type="text" name="billing[address_line1]" id="billing_address_line1" value="{{ old('billing.address_line1') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('billing.address_line1')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                                        <input type="text" name="billing[address_line2]" id="billing_address_line2" value="{{ old('billing.address_line2') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('billing.address_line2')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                        <input type="text" name="billing[city]" id="billing_city" value="{{ old('billing.city') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('billing.city')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                        <input type="text" name="billing[state]" id="billing_state" value="{{ old('billing.state') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('billing.state')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                        <input type="text" name="billing[postal_code]" id="billing_postal_code" value="{{ old('billing.postal_code') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('billing.postal_code')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                        <input type="text" name="billing[country]" id="billing_country" value="{{ old('billing.country') }}" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('billing.country')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-8">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <div class="p-3 bg-white border border-gray-300 rounded-lg">
                                <p class="text-sm text-gray-700">Cash on Delivery (COD)</p>
                                <input type="hidden" name="payment_method" value="cod">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Pay when your order arrives!</p>
                        </div>

                        <button type="submit" class="mt-8 w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-all duration-300">
                            Place Your Order
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            $("#checkoutForm").validate({
                rules: {
                    name: { required: true, minlength: 2 },
                    email: { required: true, email: true },
                    phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
                    shipping_address_id: {
                        required: function() {
                            return $('#shipping_address_id').length && $('#shipping_address_id').val() === '';
                        }
                        //This rule checks if the user must select an address from the dropdown.
                        //If the dropdown doesn’t exist (new user), this rule is skipped entirely.
                    },
                    'shipping[address_line1]': {
                        required: function() { return $('#shipping_address_id').val() === 'new' || !$('#shipping_address_id').length; },
                        minlength: 5
                        // Ensures the address_line1 field is filled out only when a new shipping address is needed.
                        // $('#shipping_address_id').val() === 'new': True if the user selects "Add a New Shipping Address" from the dropdown.
                        // !$('#shipping_address_id').length: True if there’s no dropdown (e.g., for new users who don’t have saved addresses).
                    },
                    'shipping[city]': {
                        required: function() { return $('#shipping_address_id').val() === 'new' || !$('#shipping_address_id').length; },
                        minlength: 2
                    },
                    'shipping[state]': {
                        required: function() { return $('#shipping_address_id').val() === 'new' || !$('#shipping_address_id').length; },
                        minlength: 2
                    },
                    'shipping[postal_code]': {
                        required: function() { return $('#shipping_address_id').val() === 'new' || !$('#shipping_address_id').length; },
                        digits: true, minlength: 4, maxlength: 10
                    },
                    'shipping[country]': {
                        required: function() { return $('#shipping_address_id').val() === 'new' || !$('#shipping_address_id').length; },
                        minlength: 2
                    },
                    billing_address_id: {
                        required: function() {
                            return $('#billing_address_id').length && $('#billing_address_id').val() === '';
                        }
                    },
                    'billing[address_line1]': {
                        required: function() { return $('#billing_address_id').val() === 'new' || !$('#billing_address_id').length; },
                        minlength: 5
                    },
                    'billing[city]': {
                        required: function() { return $('#billing_address_id').val() === 'new' || !$('#billing_address_id').length; },
                        minlength: 2
                    },
                    'billing[state]': {
                        required: function() { return $('#billing_address_id').val() === 'new' || !$('#billing_address_id').length; },
                        minlength: 2
                    },
                    'billing[postal_code]': {
                        required: function() { return $('#billing_address_id').val() === 'new' || !$('#billing_address_id').length; },
                        digits: true, minlength: 4, maxlength: 10
                    },
                    'billing[country]': {
                        required: function() { return $('#billing_address_id').val() === 'new' || !$('#billing_address_id').length; },
                        minlength: 2
                    },
                },
                messages: {
                    name: { required: "Please enter your full name", minlength: "Name must be at least 2 characters" },
                    email: { required: "Please enter your email", email: "Please enter a valid email" },
                    phone: { required: "Please enter your phone", digits: "Only digits allowed", minlength: "Phone must be 10 digits", maxlength: "Phone must be 10 digits" },
                    // Add more messages as needed
                },
                errorElement: "span",
                errorClass: "text-red-500 text-xs mt-1 block",
                errorPlacement: function(error, element) { error.insertAfter(element); },
                highlight: function(element) { $(element).addClass('border-red-500'); },
                unhighlight: function(element) { $(element).removeClass('border-red-500'); }
            });
        });
    
        function toggleAddressForm(selectId, formId) {
            const select = document.getElementById(selectId);
            const form = document.getElementById(formId);

            if (select.value === 'new') {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

        const shippingSelect = document.getElementById('shipping_address_id');
        const billingSelect = document.getElementById('billing_address_id');

        if (shippingSelect) {
            shippingSelect.addEventListener('change', function() {
                toggleAddressForm('shipping_address_id', 'newShippingAddressForm');
            });
            toggleAddressForm('shipping_address_id', 'newShippingAddressForm');
        }

        if (billingSelect) {
            billingSelect.addEventListener('change', function() {
                toggleAddressForm('billing_address_id', 'newBillingAddressForm');
            });
            toggleAddressForm('billing_address_id', 'newBillingAddressForm');
        }
    
    </script>
@endsection