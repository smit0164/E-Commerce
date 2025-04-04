@extends('layouts.admin.app')

@section('title', 'Trashed Admins')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Trashed Admins</h2>
        <a href="{{ route('admin.admins.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Admins
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($admins as $admin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-5 whitespace-nowrap">{{ $admin->id }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $admin->name }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $admin->email }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $admin->role->name ?? 'N/A' }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full 
                                {{ $admin->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $admin->status === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">{{ $admin->deleted_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-5 whitespace-nowrap flex space-x-4">
                            <!-- Restore Button -->
                            <form action="{{ route('admin.admins.restore',$admin->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-green-600 hover:text-green-800" 
                                        title="Restore"
                                        onclick="return confirm('Are you sure you want to restore this admin?')">
                                    <i class="fas fa-undo w-5 h-5"></i>
                                </button>
                            </form>

                            <!-- Permanently Delete Button -->
                            <form action="{{ route('admin.admins.force_delete',$admin->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800" 
                                        title="Permanently Delete"
                                        onclick="return confirm('Are you sure you want to permanently delete this admin? This action cannot be undone.')">
                                    <i class="fas fa-trash-alt w-5 h-5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-5 text-center text-gray-500">No trashed admins found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $admins->links() }}
    </div>
</div>
@endsection
