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
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium  rounded-full 
                            {{ $block->is_active==='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $block->is_active==='active' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-5 whitespace-nowrap">{{ $block->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-5 whitespace-nowrap flex space-x-4">
                        <a href="" 
                            class="text-green-600 hover:text-green-800" 
                            title="View Products">
                             <i class="fas fa-eye w-5 h-5"></i>
                         </a>
                        <a href="{{ route('admin.static_blocks.edit', $block->slug) }}" 
                           class="text-indigo-600 hover:text-indigo-800" 
                           title="Edit">
                            <i class="fas fa-edit w-5 h-5"></i>
                        </a>
                        <button type="button" 
                                class="text-red-600 hover:text-red-800" 
                                onclick="openDeleteModal('{{ $block->slug }}', '{{ $block->title }}')"
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