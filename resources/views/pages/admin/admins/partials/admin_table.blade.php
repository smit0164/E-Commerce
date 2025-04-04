<!-- resources/views/pages/admin/admins/partials/admin_table.blade.php -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($admins as $admin)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $admin->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $admin->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $admin->role?->name ?? 'No role' }}</td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $admin->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($admin->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $admin->created_at->format('M d, Y')  }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.admins.edit',$admin->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900 mr-4"  title="Edit">
                           <i class="fas fa-edit w-5 h-5"></i>
                        </a>
                        
                        <button onclick="openDeleteModal('{{ $admin->id }}', '{{ $admin->name }}')"
                                class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No admins found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>