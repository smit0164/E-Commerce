@extends('layouts.admin.app')
@section('title', 'Trashed Categories')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Trashed Categories</h2>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Categories
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
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
                        <td class="px-6 py-5 whitespace-nowrap">{{ $category->deleted_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-5 whitespace-nowrap flex space-x-4">
                            <form action="{{ route('admin.categories.restore', $category->slug) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-green-600 hover:text-green-800" 
                                        title="Restore"
                                        onclick="return confirm('Are you sure you want to restore this category?')">
                                    <i class="fas fa-undo w-5 h-5"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.categories.force-delete', $category->slug) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800" 
                                        title="Permanently Delete"
                                        onclick="return confirm('Are you sure you want to permanently delete this category? This action cannot be undone.')">
                                    <i class="fas fa-trash-alt w-5 h-5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-gray-500">No trashed categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>

<!-- Inline JavaScript -->
<script>
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