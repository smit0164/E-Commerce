@extends('layouts.admin.app')
@section('title', 'Edit Role')
@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Edit Role</h2>
    </div>

    <form id="editRoleForm" method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="space-y-4">
        @csrf
        @method('PUT') <!-- Update request -->
        
        <input type="hidden" name="is_super_admin" value="no">
        
        <!-- Role Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="Enter role name">
            <span class="error text-xs text-red-600 mt-1 hidden"></span>
            @error('name') 
                <p class="backend-error text-xs text-red-600 mt-1">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Super Admin Checkbox -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="is_super_admin" name="is_super_admin" value="yes"
                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                   {{ old('is_super_admin', $role->is_super_admin) === 'yes' ? 'checked' : '' }}>
            <label for="is_super_admin" class="text-sm text-gray-700">Super Admin</label>
        </div>

        <!-- Permissions -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50 p-3 rounded-lg border">
                @foreach($permissions as $permission)
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" 
                               value="{{ $permission->id }}"
                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                               {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label for="permission_{{ $permission->id }}" class="text-sm text-gray-700">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
            <span class="error text-xs text-red-600 mt-1 hidden"></span>
            @error('permissions') 
                <p class="backend-error text-xs text-red-600 mt-1">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.roles.index') }}" 
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" 
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center">
                 Update Role
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Custom validation method for at least one checkbox
    $.validator.addMethod("requireOne", function(value, element) {
        return $('input[name="permissions[]"]:checked').length > 0;
    }, "Please select at least one permission.");

    // Initialize form validation
    $("#editRoleForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            'permissions[]': {
                requireOne: function() {
                    return !$('#is_super_admin').is(':checked'); // Only require permissions if Super Admin is NOT checked
                }
            }
        },
        messages: {
            name: {
                required: "Role name is required",
                minlength: "Role name must be at least 2 characters long",
                maxlength: "Role name cannot exceed 50 characters"
            },
            'permissions[]': {
                requireOne: "Please select at least one permission"
            }
        },
        errorElement: "span",
        errorClass: "error text-xs text-red-600 mt-1",
        validClass: "valid",
        errorPlacement: function(error, element) {
            if (element.attr("name") === "permissions[]") {
                error.insertAfter(element.closest('.grid').next('.error'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).addClass("border-red-500").removeClass("border-gray-300");
        },
        unhighlight: function(element) {
            $(element).removeClass("border-red-500").addClass("border-gray-300");
        }
    });
});
</script>

@endsection
