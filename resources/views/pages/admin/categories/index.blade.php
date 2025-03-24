@extends('layouts.admin.app')
@section('title', 'Category Management')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Manage Categories</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.categories.trashed') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-trash-restore mr-2"></i> View Trashed Categories
            </a>
            <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Category
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-5 whitespace-nowrap">{{ $category->id }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="max-w-[50px] h-auto">
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $category->name }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $category->slug }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-5 whitespace-nowrap flex space-x-4">
                            <a href="{{ route('admin.categories.show', $category->slug) }}" 
                               class="text-green-600 hover:text-green-800" 
                               title="View Products">
                                <i class="fas fa-eye w-5 h-5"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->slug) }}" 
                               class="text-indigo-600 hover:text-indigo-800" 
                               title="Edit">
                                <i class="fas fa-edit w-5 h-5"></i>
                            </a>
                            <button type="button" 
                                    class="text-red-600 hover:text-red-800" 
                                    onclick="openDeleteModal('{{ $category->id }}', '{{ $category->slug }}', '{{ $category->name }}')"
                                    title="Move to Trash">
                                <i class="fas fa-trash w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-gray-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Move Category to Trash</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to move the category "<span id="delete-category-name"></span>" to trash? You can restore it later from the trash.</p>
                </div>
                <form id="delete-category-form" action="" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="category_id" id="delete-category-id">
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
    // Open Modal
    function openDeleteModal(categoryId, categorySlug, categoryName) {
        const modal = document.getElementById('delete-modal');
        const categoryNameSpan = document.getElementById('delete-category-name');
        const categoryIdInput = document.getElementById('delete-category-id');
        const deleteForm = document.getElementById('delete-category-form');

        categoryNameSpan.textContent = categoryName;
        categoryIdInput.value = categoryId;
        deleteForm.action = "{{ route('admin.categories.destroy', '') }}/" + categorySlug;
        modal.classList.remove('hidden');
    }

    // Close Modal
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('delete-modal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Function to show Toastr notifications
    function showNotifications() {
        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif
    }

    // Run notifications after DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof toastr !== 'undefined') {
            showNotifications();
        } else {
            console.error('Toastr is not loaded');
        }
    });
</script>
@endsection