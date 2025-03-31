@extends('layouts.admin.app')

@section('title', 'Trashed Products')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Trashed Products</h2>
            <a href="{{ route('admin.products.index') }}"
                class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted
                            At</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-5 whitespace-nowrap">{{ $product->id }}</td>
                            <td class="px-4 py-5 whitespace-nowrap">
                                @if ($product->image)
                                    <img src="{{ $product->getProductImageUrl() }}" alt="{{ $product->name }}"
                                        class="w-12 h-12 object-cover rounded">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-5 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-4 py-5 whitespace-nowrap">
                                @foreach ($product->categories as $category)
                                    {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td class="px-4 py-5 whitespace-nowrap">₹{{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-5 whitespace-nowrap">{{ $product->quantity }}</td>
                            <td class="px-4 py-5 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-5 whitespace-nowrap">{{ $product->deleted_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-5 whitespace-nowrap flex justify-center space-x-4">
                                <form action="{{ route('admin.products.restore', $product->slug) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Restore"
                                        onclick="return confirm('Are you sure you want to restore this product?')">
                                        <i class="fas fa-undo w-5 h-5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.force-delete', $product->slug) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"
                                        title="Permanently Delete"
                                        onclick="return confirm('Are you sure you want to permanently delete this product? This action cannot be undone.')">
                                        <i class="fas fa-trash-alt w-5 h-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-5 text-center text-gray-500">No trashed products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
