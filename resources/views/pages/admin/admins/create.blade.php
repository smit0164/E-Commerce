<!-- resources/views/pages/admin/admins/create.blade.php -->
@extends('layouts.admin.app')
@section('title', 'Create Admin')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Create New Admin</h2>
        <a href="{{ route('admin.admins.index') }}" 
           class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Admins
        </a>
    </div>

    <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-6">
        @csrf
        
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Enter admin name" 
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Enter admin email" 
                   required>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" 
                   id="password" 
                   name="password"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Enter password" 
                   required>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Confirm password" 
                   required>
        </div>

        <!-- Role Dropdown -->
        <div>
            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Role</label>
            <select id="role_id" 
                    name="role_id" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                    required>
                <option value="">Select a role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.admins.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center">
                <i class="fas fa-save mr-2"></i> Create Admin
            </button>
        </div>
    </form>
</div>
@endsection