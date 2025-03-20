@extends('layouts.users.app')

@section('content')

<div class="container py-12">
    <h2 class="text-3xl font-semibold text-gray-800 uppercase mb-6">{{ $category->name }}</h2>

    @if ($category->products->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($category->products as $product)
                <x-users.product-card :product="$product" />
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <h3 class="text-xl text-gray-600 font-semibold">No products available in this category.</h3>
            <p class="text-gray-500 mt-2">Please check back later or explore other categories.</p>
            <a href="{{ url('/') }}" class="mt-4 inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-opacity-90 transition">
                Go to Home
            </a>
        </div>
    @endif

</div>

@endsection
