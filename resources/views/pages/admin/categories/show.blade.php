@extends('layouts.admin.app')
@section('title', 'Category Details - ' . $category->name)
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Category: {{ $category->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Details for category ID: {{ $category->id }}</p>
        </div>
        <a href="{{ route('admin.categories.edit', $category->slug) }}" 
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
            <i class="fas fa-edit mr-2"></i> Edit Category
        </a>
    </div>

    <!-- Category Details -->
    <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Category Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-gray-700 font-medium">ID</label>
                <p class="text-gray-900 bg-white border border-gray-200 rounded-lg px-3 py-2">{{ $category->id }}</p>
            </div>
            <div class="space-y-2">
                <label class="block text-gray-700 font-medium">Name</label>
                <p class="text-gray-900 bg-white border border-gray-200 rounded-lg px-3 py-2">{{ $category->name }}</p>
            </div>
            <div class="space-y-2">
                <label class="block text-gray-700 font-medium">Slug</label>
                <p class="text-gray-900 bg-white border border-gray-200 rounded-lg px-3 py-2">{{ $category->slug }}</p>
            </div>
            <div class="space-y-2">
                <label class="block text-gray-700 font-medium">Created At</label>
                <p class="text-gray-900 bg-white border border-gray-200 rounded-lg px-3 py-2">{{ $category->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-gray-700 font-medium">Image</label>
                <div class="bg-white border border-gray-200 rounded-lg p-4 flex justify-center">
                    <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="max-w-[300px] h-auto rounded-md shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="mb-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Products in this Category</h3>
        @if($products->count() > 0)
            <div class="overflow-x-auto bg-gray-50 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr class="hover:bg-indigo-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $product->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-indigo-600 font-medium">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $product->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <p class="text-yellow-700">No products found in this category.</p>
            </div>
        @endif
    </div>

    <!-- Navigation -->
    <div class="flex justify-end">
        <a href="{{ route('admin.categories.index') }}" 
           class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Categories
        </a>
    </div>
</div>
@endsection