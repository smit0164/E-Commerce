<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5 whitespace-nowrap">{{ $category->id }}</td>
                    <td class="px-6 py-5 whitespace-nowrap">
                        <img src="{{ $category->getCategoryImageUrl() }}" alt="{{ $category->name }}" class="max-w-[50px] h-auto">
                    </td>
                    <td class="px-6 py-5 whitespace-nowrap">{{ $category->name }}</td>
                    <td class="px-6 py-5 whitespace-nowrap">{{ $category->slug }}</td>
                    <td class="px-6 py-5 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium  rounded-full 
                             {{ $category->status==='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->status==='active' ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
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
                    <td colspan="7" class="px-6 py-5 text-center text-gray-500">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>