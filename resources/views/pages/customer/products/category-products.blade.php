@extends('layouts.users.app')

@section('content')
    <div class="container mx-auto px-4 py-12 max-w-7xl">
        <!-- Category Title -->
        <h2 class="text-3xl font-semibold text-gray-800 uppercase mb-8 text-center tracking-wide">
            {{ $category->name }}
        </h2>

        <!-- Products Section -->
        <section class="relative">

            @if ($products->isNotEmpty())
                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-items-center">
                    @foreach ($products as $product)
                        <x-users.product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $products->links('pagination::simple-tailwind') }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <h3 class="text-xl text-gray-600 font-semibold">No products available in this category.</h3>
                    <p class="text-gray-500 mt-2">Please check back later or explore other categories.</p>
                    <a href="{{ url('/') }}"
                        class="mt-6 inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-opacity-90 transition-all duration-300">
                        Go to Home
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection