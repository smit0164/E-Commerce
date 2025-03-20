@extends('layouts.admin.app')

@section('title', 'Category Management')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Manage Categories</h2>

        <!-- Add Category Button -->
        <div class="flex justify-end mb-8">
            <x-admin.button data-action="open-modal" data-modal="addCategoryModal" class="bg-indigo-600 text-white hover:bg-indigo-700" icon="fas fa-plus">
                Add New Category
            </x-admin.button>
        </div>

        <!-- Categories Table -->
        <x-admin.admin-table table-id="categories-table" tbody-id="category-list" :columns="['ID', 'Image', 'Category Name', 'Created At', 'Actions']">
            @forelse ($categories as $category)
                <tr class="hover:bg-gray-50 transition duration-200" data-category-id="{{ $category->id }}">
                    <td class="px-6 py-5 font-medium">{{ $category->id }}</td>
                    <td class="px-6 py-5">
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="max-w-[50px] h-auto">
                    </td>
                    <td class="px-6 py-5">{{ $category->name }}</td>
                    <td class="px-6 py-5">{{ $category->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-5 flex space-x-4">
                        <button data-action="open-modal" data-modal="editCategoryModal" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}" data-category-slug="{{ $category->slug }}" data-category-image="{{ $category->image }}" class="text-indigo-600 hover:text-indigo-800 transition edit-category" title="Edit Category">
                            <i class="fas fa-edit w-5 h-5"></i>
                        </button>
                        <button data-action="open-modal" data-modal="deleteCategoryModal" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}" class="text-red-600 hover:text-red-800 transition" title="Delete Category">
                            <i class="fas fa-trash w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-5 text-center text-gray-500">No categories found.</td>
                </tr>
            @endforelse
        </x-admin.admin-table>
    </div>

    <!-- Add Category Modal -->
    <x-admin.modal modal-id="addCategoryModal" title="Add New Category">
        <form id="addCategoryForm" class="space-y-6" enctype="multipart/form-data">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Category Name</label>
                <input type="text" name="name" id="category-name" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700" placeholder="Enter category name">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Slug (Auto-generated)</label>
                <input type="text" name="slug" id="category-slug" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-600" placeholder="Slug will appear here" readonly>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Category Image</label>
                <div id="current-image-preview-add" class="mb-2"></div>
                <input type="file" name="image" id="category-image" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700">
            </div>
            <div class="flex justify-end space-x-3">
                <x-admin.button type="button" data-action="close-modal" data-modal="addCategoryModal" class="bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</x-admin.button>
                <x-admin.button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700">Save</x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Edit Category Modal -->
    <x-admin.modal modal-id="editCategoryModal" title="Edit Category">
        <form id="editCategoryForm" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="edit-category-id">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Category Name</label>
                <input type="text" name="name" id="edit-category-name" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700" placeholder="Enter category name">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Slug (Auto-generated)</label>
                <input type="text" name="slug" id="edit-category-slug" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-600" readonly>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Category Image</label>
                <div id="current-image-preview" class="mb-2"></div>
                <input type="file" name="image" id="edit-category-image" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700">
            </div>
            <div class="flex justify-end space-x-3">
                <x-admin.button type="button" data-action="close-modal" data-modal="editCategoryModal" class="bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</x-admin.button>
                <x-admin.button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700">Update</x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Delete Confirmation Modal -->
    <x-admin.modal modal-id="deleteCategoryModal" title="Confirm Deletion">
        <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="delete-category-name" class="font-medium"></span>? This action cannot be undone.</p>
        <form id="delete-category-form" method="POST" class="space-y-6">
            @csrf
            @method('DELETE')
            <input type="hidden" name="category_id" id="delete-category-id">
            <div class="flex justify-end space-x-3">
                <x-admin.button type="button" data-action="close-modal" data-modal="deleteCategoryModal" class="bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</x-admin.button>
                <x-admin.button type="submit" class="bg-red-600 text-white hover:bg-red-700">Delete</x-admin.button>
            </div>
        </form>
    </x-admin.modal>

  
  
    @vite(['resources/js/admin/categories.js'])
@endsection