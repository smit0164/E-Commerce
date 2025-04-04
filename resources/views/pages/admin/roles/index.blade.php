@extends('layouts.admin.app')
@section('title', 'Role Management')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Manage Roles</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.roles.trashed') }}" 
               class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-trash-restore mr-2"></i> View Trashed Roles
            </a>
            <a href="{{ route('admin.roles.create') }}" 
               class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Role
            </a>
        </div>
    </div>

    <!-- Enhanced Search and Filter Section -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search-roles" class="block text-sm font-medium text-gray-700 mb-1">Search Roles</label>
                <div class="relative">
                    <input type="text" 
                           id="search-roles" 
                           name="search"
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                           placeholder="Search by name...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <div class="flex space-x-2">
                    <input type="date" 
                           id="date_start-roles" 
                           name="date_start"
                           class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="date" 
                           id="date_end-roles" 
                           name="date_end"
                           class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
           
        </div>


    </div>

    <div id="role-table-container">
        @include('pages.admin.roles.partials.role_table')
    </div>
    <div class="mt-6" id="pagination-roles">
        {{ $roles->links('pagination::simple-tailwind') }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal-roles" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Move Role to Trash</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to move the role "<span id="delete-role-name"></span>" to trash? You can restore it later from the trash.</p>
                </div>
                <form id="delete-role-form" action="" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-4">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" 
                                onclick="closeDeleteModal()">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Move to Trash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(roleId, roleName) {
        const modal = document.getElementById('delete-modal-roles');
        document.getElementById('delete-role-name').textContent = roleName;
        document.getElementById('delete-role-form').action = "{{ route('admin.roles.destroy', '') }}/" + roleId;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal-roles').classList.add('hidden');
    }

    document.getElementById('delete-modal-roles').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    $(document).ready(function () {
        function fetchRoles(page = 1) {
            let search = $('#search-roles').val();
            let dateStart = $('#date_start-roles').val();
            let dateEnd = $('#date_end-roles').val();

            $.ajax({
                url: "{{ route('admin.roles.index') }}",
                method: "GET",
                data: {
                    search: search,
                    date_start: dateStart,
                    date_end: dateEnd,
                    page: page
                },
                success: function (response) {
                    $("#role-table-container").html(response.html);
                    $('#pagination-roles').html(response.pagination);
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        // Event Listeners for Filters
        $('#search-roles, #date_start-roles, #date_end-roles, #status-roles, #sort-roles').on('change input', function() {
            fetchRoles();
        });

        // Pagination Click Handler
        $(document).on('click', '#pagination-roles a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let page = new URL(url).searchParams.get('page');
            fetchRoles(page);
        });
    });
</script>
@endsection