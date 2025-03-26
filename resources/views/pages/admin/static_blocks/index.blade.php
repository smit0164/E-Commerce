@extends('layouts.admin.app')

@section('title', 'Static Block Management')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Manage Static Blocks</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.static_blocks.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Block
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($blocks as $block)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-5 whitespace-nowrap">{{ $block->id }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $block->title }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $block->slug }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white rounded-full 
                                {{ $block->is_active===1 ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $block->is_active===1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $block->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-5 whitespace-nowrap flex space-x-4">
                            <a href="{{ route('admin.static_blocks.edit', $block->id) }}" 
                               class="text-indigo-600 hover:text-indigo-800" 
                               title="Edit">
                                <i class="fas fa-edit w-5 h-5"></i>
                            </a>
                            <button type="button" 
                                    class="text-red-600 hover:text-red-800" 
                                    onclick="openDeleteModal('{{ $block->id }}', '{{ $block->title }}')"
                                    title="Delete">
                                <i class="fas fa-trash w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-gray-500">No static blocks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $blocks->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Static Block</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to delete the static block "<span id="delete-block-name"></span>"? This action cannot be undone.</p>
                </div>
                <form id="delete-block-form" action="" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="block_id" id="delete-block-id">
                    <div class="flex justify-center space-x-4">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" 
                                onclick="closeDeleteModal()">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Delete
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
    function openDeleteModal(blockId, blockName) {
        const modal = document.getElementById('delete-modal');
        const blockNameSpan = document.getElementById('delete-block-name');
        const blockIdInput = document.getElementById('delete-block-id');
        const deleteForm = document.getElementById('delete-block-form');

        blockNameSpan.textContent = blockName;
        blockIdInput.value = blockId;
        deleteForm.action = "{{ route('admin.static_blocks.destroy', '') }}/" + blockId;
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

    // Function to show Toastr notifications (optional, if Toastr is included)
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
            console.log('Toastr is not loaded');
        }
    });
</script>
@endsection