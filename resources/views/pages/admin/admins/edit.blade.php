@extends('layouts.admin.app')

@section('title', 'Edit Admin')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-4xl mx-auto my-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Admin</h2>
    </div>



    <form action="{{ route('admin.admins.update',$admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required 
                   class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required 
                   class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role_id" id="role_id" required class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $admin->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">New Password (Leave blank if not changing)</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.admins.index') }}"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Update 
            </button>
        </div>
    </form>
</div>
@endsection
