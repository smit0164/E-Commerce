@extends('layouts.users.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        @if ($products->count() > 0)
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Search Results:{{$searchTerm}}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="transition-transform duration-200 hover:scale-105">
                        <x-users.product-card :product="$product" />
                    </div>
                @endforeach
            </div>
            <!-- Pagination (if applicable) -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No products found matching your filters.</p>
                <a href="{{ route('products.index') }}" class="text-primary hover:underline mt-4 inline-block">Browse All Products</a>
            </div>
        @endif
    </div>
@endsection