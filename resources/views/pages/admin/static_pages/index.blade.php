@extends('layouts.admin.app')

@section('title', 'Static Page Management')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Manage Static Pages</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.static_pages.trashed') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-trash-restore mr-2"></i> View Trashed Static Pages
            </a>
            <a href="{{ route('admin.static_pages.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Page
            </a>
        </div>
    </div>
   
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search-static-pages" class="block text-sm font-medium text-gray-700 mb-1">Search Pages</label>
                <div class="relative">
                    <input type="text" id="search-static-pages" name="search" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search by title...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <div class="flex space-x-2">
                    <input type="date" id="date_start-static-pages" name="date_start" class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="date" id="date_end-static-pages" name="date_end" class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>
    </div>

    <div id="static-pages-table-container">
        @include('pages.admin.static_pages.partials.static_pages_table')
    </div>
    <div class="mt-6" id="pagination-static_pages">
        {{ $pages->links('pagination::simple-tailwind') }}
    </div>

    <div id="delete-modal-static-pages" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Move Page to Trash</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to move "<span id="delete-page-name"></span>" to trash?</p>
                </div>
                <form id="delete-page-form" action="" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-4">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="closeDeleteModal()">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Move to Trash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(pageSlug, pageName) {
        const modal = document.getElementById('delete-modal-static-pages');
        const pageNameSpan = document.getElementById('delete-page-name');
        const deleteForm = document.getElementById('delete-page-form');

        pageNameSpan.textContent = pageName;
        deleteForm.action = "{{ route('admin.static_pages.destroy', '') }}/" + pageSlug;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal-static-pages').classList.add('hidden');
    }

    document.getElementById('delete-modal-static-pages').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });

    $(document).ready(function () {
        function fetchStaticPages(page = 1) {
            let search = $('#search-static-pages').val();
            let dateStart = $('#date_start-static-pages').val();
            let dateEnd = $('#date_end-static-pages').val();

            $.ajax({
                url: "{{ route('admin.static_pages.index') }}",
                method: "GET",
                data: {
                    search: search,
                    date_start: dateStart,
                    date_end: dateEnd,
                    page: page,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $("#static-pages-table-container").html(response.html);
                    $('#pagination-static_pages').html(response.pagination);
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        $('#search-static-pages, #date_start-static-pages, #date_end-static-pages').on('input', function () {
            fetchStaticPages();
        });

        $(document).on('click', '#pagination-static_pages a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchStaticPages(page);
        });
    });
</script>
@endsection