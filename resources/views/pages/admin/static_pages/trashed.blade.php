@extends('layouts.admin.app')

@section('title', 'Trashed Static Pages')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Trashed Static Pages</h2>
        <a href="{{ route('admin.static_pages.index') }}"
           class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Static Pages
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($pages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-5 whitespace-nowrap">{{ $page->id }}</td>
                        <td class="px-4 py-5 whitespace-nowrap">{{ $page->title }}</td>
                        <td class="px-4 py-5 whitespace-nowrap">{{ $page->slug }}</td>
                        <td class="px-4 py-5 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $page->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $page->status === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-5 whitespace-nowrap">{{ $page->deleted_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-5 whitespace-nowrap flex justify-center space-x-4">
                            <!-- Restore Form -->
                            <form action="{{ route('admin.static_pages.restore', $page->slug) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800" title="Restore"
                                    onclick="return confirm('Are you sure you want to restore this static page?')">
                                    <i class="fas fa-undo w-5 h-5"></i>
                                </button>
                            </form>

                            <!-- Permanently Delete Form -->
                            <form action="{{ route('admin.static_pages.force_delete', $page->slug) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Permanently Delete"
                                    onclick="return confirm('Are you sure you want to permanently delete this static page? This action cannot be undone.')">
                                    <i class="fas fa-trash-alt w-5 h-5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-gray-500">No trashed static pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pages->links() }}
    </div>
</div>
@endsection