<!-- resources/views/pages/admin/roles/partials/role_table.blade.php -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($roles as $role)
               
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $role->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $role->permissions->pluck('name')->implode(', ') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $role->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.roles.edit',$role->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900 mr-4">
                           <i class="fas fa-edit w-5 h-5"></i>
                        </a>

                        @php
                         $isDisabled = (bool)($role->admins->count()>0);
                        @endphp
                        <button onclick="openDeleteModal('{{ $role->id }}', '{{ $role->name }}')"
                            class="text-red-600 hover:text-red-900 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $isDisabled ? 'disabled' : '' }}>
                            <i class="fas fa-trash w-5 h-5"></i>
                        </button>
                    
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No roles found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>