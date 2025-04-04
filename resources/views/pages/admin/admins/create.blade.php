<!-- resources/views/pages/admin/admins/create.blade.php -->
@extends('layouts.admin.app')
@section('title', 'Create Admin')
@section('content')
<div class="bg-white p-6 rounded-xl shadow-lg max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create New Admin</h2>

    </div>
    @if ($errors->any() || session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <h3 class="text-lg font-semibold">Errors:</h3>
        <ul class="list-disc list-inside">
            @if (session('error'))
                <li>{{ session('error') }}</li>
            @endif
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="{{ route('admin.admins.store') }}" id="adminForm">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Enter admin name" required>
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
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Enter admin email" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Enter password" required>
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
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Confirm password" required>
            </div>
        </div>

        <!-- Role Dropdown -->
        <div class="mt-4">
            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Role</label>
            <div class="relative">
                <select id="role_id" 
                        name="role_id" 
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white">
                    <option value="">Select a role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-500"></i>
            </div>
            @error('role_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.admins.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center">
                 Create Admin
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#adminForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                role_id: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Name must be at least 3 characters"
                },
                email: {
                    required: "Email is required",
                    email: "Enter a valid email"
                },
                password: {
                    required: "Password is required",
                    minlength: "Password must be at least 6 characters"
                },
                password_confirmation: {
                    required: "Confirm your password",
                    equalTo: "Passwords do not match"
                },
                role_id: {
                    required: "Select a role"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-sm text-red-600 mt-1");
                element.addClass("border-red-500");
                error.insertAfter(element);
            },
            success: function (label, element) {
                $(element).removeClass("border-red-500");
            }
        });
    });
</script>
@endsection
