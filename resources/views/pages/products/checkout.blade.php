@extends('layouts.users.app')

@section('content')
    <div class="w-full max-w-4xl mx-auto py-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Checkout</h2>

        @if (empty($cartItems))
            <p class="text-gray-600 text-center">Your cart is empty. <a href="{{ route('home') }}" class="text-red-600">Continue Shopping</a></p>
        @else
            <div class="flex flex-col lg:flex-row items-start gap-6">
                <!-- Order Summary -->
                <div class="lg:w-1/3 flex-shrink-0">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                        <ul class="space-y-2">
                            @foreach ($cartItems as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                    <span>₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <hr class="my-4">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium text-gray-700">Total:</p>
                            <p class="text-xl font-bold text-red-500">₹{{ number_format($totalPrice, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="lg:w-2/3 flex-1">
                    <form action="{{ route('checkout.store') }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        @csrf
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping Information</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" name="phone" id="phone" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address Line</label>
                                <textarea name="address" id="address" class="w-full border-gray-300 rounded-lg p-2" required></textarea>
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" id="city" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" name="state" id="state" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" name="country" id="country" class="w-full border-gray-300 rounded-lg p-2" required>
                                @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Payment Method Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <div class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-lg">
                                    <p class="text-sm text-gray-700">Cash on Delivery (COD)</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">You will pay when the order is delivered.</p>
                            </div>
                        </div>

                        <button type="submit" class="mt-6 w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection