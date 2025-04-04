@extends('layouts.admin.app')

@section('title', 'Trashed Roles')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto my-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Trashed Roles</h2>
        <a href="{{ route('admin.roles.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Roles
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admins Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($roles->items() as $role)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5 whitespace-nowrap">{{ $role->id }}</td>
                    <td class="px-6 py-5 whitespace-nowrap">{{ $role->name }}</td>
                    <td class="px-6 py-5 whitespace-nowrap">{{ $role->admins->count() }}</td>
                    <td class="px-6 py-5 whitespace-nowrap">{{ $role->deleted_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-5 whitespace-nowrap flex space-x-4">
                        <form action="{{ route('admin.roles.restore',$role->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800"
                                   onclick="return confirm('Are you sure you want to restore this role?')">
                                <i class="fas fa-undo w-5 h-5"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.roles.force_delete',$role->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"
                            onclick="return confirm('Are you sure you want to permanently delete this role? This action cannot be undone.')">
                                <i class="fas fa-trash-alt w-5 h-5"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $roles->links() }}
    </div>
</div>
@endsection
