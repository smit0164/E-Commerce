@extends('layouts.users.app')

@section('content')
    <div class="w-full max-w-6xl mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Complete Your Order</h2>

      @if(empty($cartItems))
            <p class="text-gray-600 text-center">
                Your cart is empty, friend! 
                <a href="{{ route('home') }}" class="text-red-600 hover:underline">Explore Our Shop</a>
            </p>
        @else
            <div class="flex flex-col lg:flex-row-reverse items-start gap-8">
                <!-- Order Summary (Right Side) -->
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

                <!-- Checkout Form (Left Side) -->
                <div class="lg:w-2/3 flex-1">
                    <form action="{{ route('checkout.store') }}" method="POST" class="bg-white border border-gray-200 rounded-xl p-8 shadow-md max-w-4xl mx-auto" id="checkoutForm">
                        @csrf

                        <!-- Personal Details -->
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Your Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ auth('customer')->user()->name ?? '' }}" class="w-full border-gray-300 rounded-lg p-3" required>
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ auth('customer')->user()->email ?? '' }}" class="w-full border-gray-300 rounded-lg p-3" required>
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="tel" name="phone" id="phone" value="{{ auth('customer')->user()->phone ?? '' }}" pattern="[0-9]{10}" maxlength="10" class="w-full border-gray-300 rounded-lg p-3" required>
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                <div class="p-3 bg-white border border-gray-300 rounded-lg">
                                    <p class="text-sm text-gray-700">Cash on Delivery (COD)</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pay when your order arrives!</p>
                            </div>
                        </div>

                        <!-- Shipping Address Section -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Where Should We Deliver?</h4>

                            <!-- Address Selection -->
                            @if (auth('customer')->user()->addresses && auth('customer')->user()->addresses->isNotEmpty())
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select an Existing Address</label>
                                    <select name="address_id" id="address_id" class="w-full border-gray-300 rounded-lg p-3">
                                        @php
                                            $defaultAddress = auth('customer')->user()->addresses->firstWhere('is_default', true);
                                        @endphp
                                        <option value="{{ $defaultAddress ? $defaultAddress->id : '' }}" selected>
                                            {{ $defaultAddress ? $defaultAddress->address_line . ', ' . $defaultAddress->city . ', ' . $defaultAddress->state . ' ' . $defaultAddress->postal_code . ', ' . $defaultAddress->country : '-- Select an Address --' }}
                                        </option>
                                        @foreach (auth('customer')->user()->addresses as $address)
                                            @if (!$address->is_default)
                                                <option value="{{ $address->id }}">
                                                    {{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}, {{ $address->country }}
                                                </option>
                                            @endif
                                        @endforeach
                                        <option value="new">Add a New Address</option>
                                    </select>
                                    @error('address_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div id="newAddressForm" class="hidden">
                            @else
                                <div id="newAddressForm">
                            @endif
                                <!-- New Address Form -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <input type="text" name="address" id="address" placeholder="Address" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <input type="text" name="city" id="city" placeholder="City" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <input type="text" name="state" id="state" placeholder="State" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('state') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <input type="text" name="postal_code" id="postal_code" placeholder="Postal Code" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('postal_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <input type="text" name="country" id="country" placeholder="Country" class="w-full border-gray-300 rounded-lg p-3">
                                        @error('country') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="mt-8 w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-all duration-300">
                            Place Your Order
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#checkoutForm").validate({
                rules: {
                    name: { required: true, minlength: 2 },
                    email: { required: true, email: true },
                    phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
                    address_id: { required: true }, // Require address_id to ensure something is selected
                    address: { required: function() { return $('#address_id').val() === 'new'; }, minlength: 5 },
                    city: { required: function() { return $('#address_id').val() === 'new'; }, minlength: 2 },
                    state: { required: function() { return $('#address_id').val() === 'new'; }, minlength: 2 },
                    postal_code: { required: function() { return $('#address_id').val() === 'new'; }, digits: true, minlength: 4, maxlength: 10 },
                    country: { required: function() { return $('#address_id').val() === 'new'; }, minlength: 2 }
                },
                messages: {
                    name: { required: "Please enter your full name", minlength: "Name must be at least 2 characters" },
                    email: { required: "Please enter your email", email: "Please enter a valid email" },
                    phone: { required: "Please enter your phone", digits: "Only digits allowed", minlength: "Phone must be 10 digits", maxlength: "Phone must be 10 digits" },
                    address_id: { required: "Please select an address or add a new one" },
                    address: { required: "Please enter your address", minlength: "Address must be at least 5 characters" },
                    city: { required: "Please enter your city", minlength: "City must be at least 2 characters" },
                    state: { required: "Please enter your state", minlength: "State must be at least 2 characters" },
                    postal_code: { required: "Please enter your postal code", digits: "Only digits allowed", minlength: "Postal code must be at least 4 digits", maxlength: "Postal code cannot exceed 10 digits" },
                    country: { required: "Please enter your country", minlength: "Country must be at least 2 characters" }
                },
                errorElement: "span",
                errorClass: "text-red-500 text-xs mt-1 block",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('border-red-500');
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-red-500');
                }
            });

            $('#address_id').on('change', function() {
                const newAddressForm = $('#newAddressForm');
                if (this.value === 'new') {
                    newAddressForm.removeClass('hidden');
                } else {
                    newAddressForm.addClass('hidden');
                }
            });
            $('#address_id').trigger('change');
        });
    </script>
@endsection