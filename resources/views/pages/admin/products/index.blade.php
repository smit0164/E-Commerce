@extends('layouts.admin.app')
@section('title', 'Product Management')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6"> <!-- Adjusted max-w-10xl to max-w-7xl -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Manage Products</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.products.trashed') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-trash-restore mr-2"></i> View Trashed Products
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Product
            </a>
        </div>
    </div>
    <div class="overflow-x-auto"> <!-- Added wrapper for horizontal scrolling if needed -->
        <table class="w-full divide-y divide-gray-200 table-auto"> <!-- Changed min-w-full to w-full, added table-auto -->
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50">Actions</th> <!-- Sticky Actions column -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-5">{{ $product->id }}</td> <!-- Reduced padding -->
                        <td class="px-4 py-5">
                            @if ($product->image)
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-[50px] h-auto">
                            @else
                                <span class="text-gray-500">No Image</span>
                            @endif
                        </td>
                        <td class="px-4 py-5 truncate max-w-xs">{{ $product->name }}</td> <!-- Added truncate and max-w -->
                        <td class="px-4 py-5 truncate max-w-xs">{{ $product->categories->pluck('name')->implode(', ') ?: 'No Categories' }}</td>
                        <td class="px-4 py-5">₹{{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-5">{{ $product->quantity }}</td>
                        <td class="px-4 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-5">{{ $product->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-5 flex space-x-3 sticky right-0 bg-white shadow-[inset_10px_0_10px_-10px_rgba(0,0,0,0.1)]"> <!-- Sticky with shadow -->
                            <a href="{{ route('admin.products.show', $product->slug) }}" 
                               class="text-green-600 hover:text-green-800" 
                               title="View Product">
                                <i class="fas fa-eye w-5 h-5"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->slug) }}" 
                               class="text-indigo-600 hover:text-indigo-800" 
                               title="Edit">
                                <i class="fas fa-edit w-5 h-5"></i>
                            </a>
                            <button type="button" 
                                    class="text-red-600 hover:text-red-800" 
                                    onclick="openDeleteModal('{{ $product->id }}', '{{ $product->slug }}', '{{ $product->name }}')"
                                    title="Move to Trash">
                                <i class="fas fa-trash w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-5 text-center text-gray-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $products->links('pagination::simple-tailwind') }}
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Move Product to Trash</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to move the product "<span id="delete-product-name"></span>" to trash? You can restore it later from the trash.</p>
                </div>
                <form id="delete-product-form" action="" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="product_id" id="delete-product-id">
                    <div class="flex justify-center space-x-4">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" 
                                onclick="closeDeleteModal()">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Move to Trash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Inline JavaScript -->
<script>
    function openDeleteModal(productId, productSlug, productName) {
        const modal = document.getElementById('delete-modal');
        const productNameSpan = document.getElementById('delete-product-name');
        const productIdInput = document.getElementById('delete-product-id');
        const deleteForm = document.getElementById('delete-product-form');

        productNameSpan.textContent = productName;
        productIdInput.value = productId;
        deleteForm.action = "{{ route('admin.products.destroy', '') }}/" + productSlug;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    document.getElementById('delete-modal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    function showNotifications() {
        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif
        @if (session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof toastr !== 'undefined') {
            showNotifications();
        } else {
            console.error('Toastr is not loaded');
        }
    });
</script>
@endsection