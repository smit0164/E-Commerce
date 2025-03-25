@extends('layouts.users.app')

@section('content')
    <div class="w-full max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="text-center mb-10">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
            <p class="text-lg text-gray-600">Thank you for your purchase!</p>
            <p class="text-xl font-semibold text-red-600 mt-2">Order ID: #{{ $order->id }}</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md">
            <!-- Order Items -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Order</h2>
                <ul class="space-y-3">
                    @foreach ($order->orderItems as $item)
                        <li class="flex justify-between items-center bg-gray-50 p-3 rounded-md">
                            <span class="text-gray-800 font-medium">
                                {{ $item->product_name }} <span class="text-gray-500 text-sm">(x{{ $item->quantity }})</span>
                            </span>
                            <span class="text-gray-900 font-semibold">
                                ₹{{ number_format($item->subtotal, 2) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
                <hr class="my-4 border-gray-200">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-700">Total:</p>
                    <p class="text-xl font-bold text-red-600">
                        ₹{{ number_format($order->orderItems->sum('subtotal'), 2) }}
                    </p>
                </div>
            </div>

            
        </div>

        <!-- Action -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="inline-block bg-red-600 text-white py-2 px-6 rounded-lg font-semibold hover:bg-red-700 transition-all duration-300">
                Back to Home
            </a>
        </div>
    </div>
@endsection