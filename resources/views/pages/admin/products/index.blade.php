@extends('layouts.admin.app')

@section('title', 'Product Management')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Manage Products</h2>

        <!-- Add Product Button -->
        <div class="flex justify-end mb-8">
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-5 py-2.5 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Product
            </a>
        </div>

        <!-- Product Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200" id="products-table">
                <thead class="bg-gray-50">
                    <tr class="text-left text-sm font-semibold text-gray-900">
                        @foreach (['ID', 'Image', 'Product Name', 'Category', 'Price', 'Quantity', 'Status', 'Created At', 'Actions'] as $column)
                            <th class="py-3 px-6">{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="product-list">
                    @forelse ($products as $product)
                        <tr class="border-b hover:bg-gray-50" data-product-id="{{ $product->id }}">
                            <td class="py-4 px-6 text-sm text-gray-700">{{ $product->id }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                @if ($product->image)
                                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">{{ $product->name }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                {{ $product->categories->pluck('name')->implode(', ') ?: 'No Categories' }}
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">₹{{ number_format($product->price, 2) }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700">{{ $product->quantity }}</td>
                            <td class="py-4 px-6 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $product->status === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->status === 1 ? 'Active' : 'Inactive' }}
                               </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">{{ $product->created_at->format('d M Y H:i') }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                <a href="{{ route('admin.products.edit', $product->slug) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button type="button" class="text-red-600 hover:text-red-900 delete-btn" 
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-4 px-6 text-sm text-gray-700 text-center">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Manual Delete Confirmation Modal -->
    <div id="deleteProductModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300" data-modal>
        <div class="bg-white p-8 rounded-xl w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Confirm Deletion</h3>
                <button data-modal="deleteProductModal" class="text-gray-500 hover:text-gray-700 transition closedelete-btn">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="delete-product-name" class="font-medium"></span>? This action cannot be undone.</p>
            <form id="delete-product-form" method="POST" class="space-y-6">
                @csrf
                @method('DELETE')
                <input type="hidden" name="product_id" id="delete-product-id">
                <div class="flex justify-end space-x-3">
                    <button type="button"  data-modal="deleteProductModal" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-md closedelete-btn">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md">Delete</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            const $productList = $('#product-list');

            $('.delete-btn').on('click', function () {
                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                openDeleteModal(productId, productName);
            });

            $('.closedelete-btn').on('click', function () {
                const modalId = $(this).data('modal');
                $('#' + modalId).addClass('hidden');
            });

            function openDeleteModal(productId, productName) {
                const modal = $('#deleteProductModal');
                $('#delete-product-name').text(productName);
                $('#delete-product-id').val(productId);
                modal.removeClass('hidden');
            }

            $('#delete-product-form').on('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route("admin.products.destroy") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            const productId = formData.get('product_id');
                            $(`tr[data-product-id="${productId}"]`).remove();
                            const modal = $('#deleteProductModal');
                            modal.addClass('hidden');
                            if (!$productList.children().length) {
                                $productList.html(
                                    '<tr><td colspan="9" class="px-6 py-5 text-center text-gray-500">No products found.</td></tr>'
                                );
                            }
                        } else {
                            alert(response.message || 'Failed to delete product');
                        }
                    },
                    error: function (xhr) {
                        alert('Error deleting product: ' + (xhr.responseJSON?.message || xhr.statusText));
                    }
                });
            });
        });
    </script>
@endsection