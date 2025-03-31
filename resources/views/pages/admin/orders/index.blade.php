@extends('layouts.admin.app')

@section('title', 'Order Management')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center justify-between">
        Manage Orders
    </h2>

    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search Input -->
        <div>
            <label for="search-orders" class="block text-sm font-medium text-gray-700 mb-1">Search Orders</label>
            <div class="relative">
                <input type="text" id="search-orders" name="search" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search by order ID or customer...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
         <!-- Status Dropdown -->
         <div>
            <label for="status-filter-orders" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
            <select id="status-filter-orders" name="status" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
            <div class="flex space-x-2">
                <input type="date" id="date_start-orders" name="date_start" class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <input type="date" id="date_end-orders" name="date_end" class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
    </div>

   
    <div id="orders-table-container">
        @include('pages.admin.orders.partials.orders_table')
    </div>
    <!-- Orders Table -->
   
         <div class="mt-2" id="pagination-orders">
            {{ $orders->links('pagination::simple-tailwind') }}
        </div>
    
</div>
<script>
     $(document).ready(function () {
        function fetchOrders(page = 1) {
            let search = $('#search-orders').val();
            let status = $('#status-filter-orders').val();
            let dateStart = $('#date_start-orders').val();
            let dateEnd = $('#date_end-orders').val();
            $.ajax({
                url: "{{ route('admin.orders.index') }}",
                method: "GET",
                data: {
                    search: search,
                    status: status,
                    date_start: dateStart,
                    date_end: dateEnd,
                    page: page
                },
                success: function (response) {
                    $("#orders-table-container").html(response.html);
                    $('#pagination-orders').html(response.pagination);
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        $('#search-orders, #status-filter-orders, #date_start-orders, #date_end-orders').on('input change', function () {
            fetchOrders();
        });

        $(document).on('click', '#pagination-orders a', function (e) {
            e.preventDefault();
            console.log("hii");
            let url = $(this).attr('href');
            let page = new URL(url).searchParams.get('page');
            fetchOrders(page);
        });

    });



</script>
@endsection