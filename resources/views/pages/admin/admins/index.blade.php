<!-- resources/views/pages/admin/admins/index.blade.php -->
@extends('layouts.admin.app')
@section('title', 'Admin Management')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Manage Admins</h2>
        <div class="flex space-x-4">
            <a href="" 
               class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-trash-restore mr-2"></i> View Trashed Admins
            </a>
            <a href="{{ route('admin.admins.create') }}" 
               class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Admin
            </a>
        </div>
    </div>

    <div id="admin-table-container">
        @include('pages.admin.admins.partials.admin_table')
    </div>
    <div class="mt-6" id="pagination-admin">
        {{ $admins->links('pagination::simple-tailwind') }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal-admin" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Move Admin to Trash</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to move the admin "<span id="delete-admin-name"></span>" to trash? You can restore it later from the trash.</p>
                </div>
                <form id="delete-admin-form" action="" method="POST" class="mt-4">
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

{{-- <script>
    function openDeleteModal(adminId, adminName) {
        const modal = document.getElementById('delete-modal-admin');
        const adminNameSpan = document.getElementById('delete-admin-name');
        const deleteForm = document.getElementById('delete-admin-form');

        adminNameSpan.textContent = adminName;
        // deleteForm.action = "{{ route('admin.admins.destroy', '') }}/" + adminId;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal-admin').classList.add('hidden');
    }

    document.getElementById('delete-modal-admin').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    $(document).ready(function () {
        function fetchAdmins(page = 1) {
            $.ajax({
                url: "{{ route('admin.admins.index') }}",
                method: "GET",
                data: { page: page },
                success: function (response) {
                    $("#admin-table-container").html(response.html);
                    $('#pagination-admin').html(response.pagination);
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        $(document).on('click', '#pagination-admin a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let page = new URL(url).searchParams.get('page');
            fetchAdmins(page);
        });
    });
</script> --}}
@endsection