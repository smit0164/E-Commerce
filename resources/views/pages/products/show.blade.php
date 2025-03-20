@extends('layouts.users.app')

@section('content')
<div class="container mx-auto px-4 py-12 flex justify-center">
    <div class="max-w-4xl w-full bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row p-8 space-y-6 md:space-y-0 md:space-x-8">
        
        <!-- Product Image (Left) -->
        <div class="w-full md:w-1/2 flex justify-center items-center p-6 bg-gray-100 rounded-lg">
            <img src="{{ asset('storage/products/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-80 h-80 object-cover rounded-lg shadow-lg">
        </div>

        <!-- Product Details (Right) -->
        <div class="w-full md:w-1/2 flex flex-col justify-between">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 leading-snug">{{ $product->name }}</h2>
                <p class="text-gray-600 text-lg mt-3 leading-relaxed">{{ $product->description }}</p>
            </div>

            <div class="mt-5 space-y-4 border-t pt-4">
                <p class="text-2xl font-semibold text-red-600">₹ {{ number_format($product->price, 2) }}</p>
                
                <!-- Available Stock -->
                <div class="flex items-center justify-between">
                    <p class="text-md font-medium text-gray-700">Available: 
                        <span class="font-bold text-gray-900">{{ $product->quantity }}</span>
                    </p>
                </div>
                
                <p class="text-md">
                    Status: 
                    <span class="{{ $product->status ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $product->status ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </p>
            </div>

            <!-- Full-width Add to Cart Button -->
            <div class="mt-6">
                @if($product->quantity > 0)
                    <button class="bg-primary border border-primary text-white px-6 py-2 font-medium rounded-lg uppercase w-full flex items-center justify-center gap-2 
                    hover:bg-transparent hover:text-primary transition-all duration-200">
                        <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                    </button>
                @else
                    <button class="w-full bg-gray-300 text-gray-600 text-xl font-bold px-6 py-3 rounded-lg cursor-not-allowed" disabled>
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
