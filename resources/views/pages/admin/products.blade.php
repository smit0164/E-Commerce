<!-- resources/views/admin/product.blade.php -->
@extends('layouts.admin.app')

@section('title', 'Product Management')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Manage Products</h2>

        <!-- Add Product Button -->
        <div class="flex justify-end mb-8">
           <x-admin.button data-action="open-modal" data-modal="addProductsModal" class="bg-indigo-600 text-white hover:bg-indigo-700" icon="fas fa-plus">
              Add New Products
           </x-admin.button>
        </div>

        <!-- Product Table -->
        <x-admin.admin-table table-id="products-table" tbody-id="product-list" 
        :columns="['ID', 'Image', 'Product Name', 'Price', 'Quantity', 'Status', 'Created At', 'Actions']">
        <tbody id="product-list"></tbody> <!-- Keep this empty, load via AJAX -->
    </x-admin.admin-table>
    
        
    </div>

    <!-- Add Product Modal -->
    <form id="addProductsForm" class="space-y-6" enctype="multipart/form-data">
        <x-admin.product-modal modal-id="addProductsModal" title="Add Product">
            <x-slot name="left">
                <div>
                    <label for="product-name" class="block text-gray-700 font-medium mb-2">Product Name</label>
                    <input type="text" name="name" id="product-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"  placeholder="Enter product name">
                </div>
                <div>
                    <label for="product-slug" class="block text-gray-700 font-medium mb-2">Product Slug</label>
                    <input type="text" name="slug" id="product-slug" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Auto-generated slug" readonly>
                </div>
                <div>
                    <label for="product-description" class="block text-gray-700 font-medium mb-2">Product Description</label>
                    <input type="text" name="description" id="product-description" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter product description">
                </div>
                <div>
                    <label for="product-price" class="block text-gray-700 font-medium mb-2">Price</label>
                    <input type="number" name="price" id="product-price" step="1"  min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter price">
                </div>
            </x-slot>
            <x-slot name="right">
                <div>
                    <label for="product-category" class="block text-gray-700 font-medium mb-2">Category</label>
                    <select name="category_id" id="product-category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="product-status" class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="status" id="product-status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="product-quantity" class="block text-gray-700 font-medium mb-2">Quantity</label>
                    <input type="number" step="1"  min="0"  name="quantity" id="product-quantity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter quantity">
                </div>
                <div>
                    <label for="product-image" class="block text-gray-700 font-medium mb-2">Product Image</label>
                    <div id="current-product-image-preview-add" class="mb-2"></div>
                    <input type="file" name="image" id="product-image" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </x-slot>
            <x-slot name="button">
                save
            </x-slot>
        </x-admin.product-modal>
    </form>
    
    
    <form id="editProductsForm" class="space-y-6" enctype="multipart/form-data">
        <x-admin.product-modal modal-id="editProductModal" title="Edit Product">
            <x-slot name="left">
                <div>
                    <label for="edit-product-name" class="block text-gray-700 font-medium mb-2">Product Name</label>
                    <input type="text" name="name" id="edit-product-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="edit-product-slug" class="block text-gray-700 font-medium mb-2">Product Slug</label>
                    <input type="text" name="slug" id="edit-product-slug" class="w-full px-4 py-3 border border-gray-300 rounded-lg" readonly>
                </div>
                <div>
                    <label for="edit-product-description" class="block text-gray-700 font-medium mb-2">Product Description</label>
                    <input type="text" name="description" id="edit-product-description" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="edit-product-price" class="block text-gray-700 font-medium mb-2">Price</label>
                    <input type="number" name="price" id="edit-product-price" step="1" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <input type="hidden" name="id" id="edit-product-id">
            </x-slot>
            <x-slot name="right">
                <div>
                    <label for="edit-product-category" class="block text-gray-700 font-medium mb-2">Category</label>
                    <select name="category_id" id="edit-product-category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit-product-status" class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="status" id="edit-product-status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="edit-product-quantity" class="block text-gray-700 font-medium mb-2">Quantity</label>
                    <input type="number" step="1" min="0" name="quantity" id="edit-product-quantity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="edit-product-image" class="block text-gray-700 font-medium mb-2">Product Image</label>
                    <input type="file" name="image" id="edit-product-image" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div id="edit-product-image-preview-container" class="mb-2"></div>
                </div>
            </x-slot>
            <x-slot name="button">
                Update
            </x-slot>

        </x-admin.product-modal>
    </form>
    
    
    
    
    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-xl w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900" id="edit-product-title">Edit Product</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <form id="edit-product-form" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Product Name</label>
                    <input type="text" name="name" id="edit-product-name" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Category</label>
                    <select name="category" id="edit-product-category" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700" required>
                        <option value="">Select Category</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Accessories">Accessories</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Price</label>
                    <input type="number" name="price" id="edit-product-price" step="0.01" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Discount (%)</label>
                    <input type="number" name="discount" id="edit-product-discount" min="0" max="100" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Stock</label>
                    <input type="number" name="stock" id="edit-product-stock" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Product Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition duration-200">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteProductModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-xl w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Confirm Deletion</h3>
                <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="delete-product-name" class="font-medium"></span>? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition duration-200">
                    Cancel
                </button>
                <button class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @vite(['resources/js/admin/products.js'])
@endsection