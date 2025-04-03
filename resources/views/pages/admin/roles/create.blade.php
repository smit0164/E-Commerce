@extends('layouts.admin.app')
@section('title', 'Create Role')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Create New Role</h2>
        <a href="{{ route('admin.roles.index') }}" 
           class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Roles
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-6">
        @csrf
        
        <!-- Role Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Enter role name" 
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Super Admin Checkbox -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Super Admin Status</label>
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_super_admin" 
                       name="is_super_admin" 
                       value="yes"
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                       {{ old('is_super_admin') === 'yes' ? 'checked' : '' }}>
                <label for="is_super_admin" 
                       class="ml-2 text-sm text-gray-700">Is Super Admin</label>
            </div>
            @error('is_super_admin')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Permissions -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                @foreach($permissions as $permission)
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="permission_{{ $permission->id }}"
                               name="permissions[]" 
                               value="{{ $permission->id }}"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                        <label for="permission_{{ $permission->id }}" 
                               class="ml-2 text-sm text-gray-700">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
            @error('permissions')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.roles.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center">
                <i class="fas fa-save mr-2"></i> Create Role
            </button>
        </div>
    </form>
</div>
@endsection