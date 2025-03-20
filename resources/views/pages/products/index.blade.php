@extends('layouts.users.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-[280px,1fr] gap-8">
        <!-- Sidebar -->
        <aside class="bg-white p-6 rounded-lg shadow-md">
            <!-- Categories -->
            <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Categories</h3>
            <div class="space-y-3">
                @foreach ($categories as $category)
                    <div class="flex items-center group">
                        <input 
                            type="checkbox" 
                            id="{{ $category['id'] }}" 
                            class="text-primary focus:ring-2 focus:ring-primary/20 rounded-sm cursor-pointer transition-colors"
                        >
                        <label 
                            for="{{ $category['id'] }}" 
                            class="text-gray-600 ml-3 cursor-pointer group-hover:text-gray-800 transition-colors flex-1"
                        >
                            {{ $category['name'] }}
                        </label>
                        <span class="ml-auto text-gray-500 text-sm">({{ $category['count'] }})</span>
                    </div>
                @endforeach
            </div>

            <!-- Price Filter -->
            <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-5 uppercase tracking-wide">Price Range</h3>
            <div class="grid grid-cols-2 gap-3">
                <input 
                    type="number" 
                    name="min" 
                    id="min" 
                    class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400"
                    placeholder="Min" 
                    min="0"
                    step="0.01"
                >
                <input 
                    type="number" 
                    name="max" 
                    id="max" 
                    class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400"
                    placeholder="Max" 
                    min="0"
                    step="0.01"
                >
            </div>
            <button 
                class="mt-4 w-full bg-primary text-white py-2 rounded-lg hover:bg-primary/90 transition-colors"
                type="button"
            >
                Apply Filters
            </button>
        </aside>

        <!-- Products Section -->
        <section class="pb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-semibold text-gray-800">Shop Products</h2>
                <span class="text-gray-600">{{ $products->count() }} items</span>
            </div>

            <!-- Responsive Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <x-users.product-card :product="$product"/>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
