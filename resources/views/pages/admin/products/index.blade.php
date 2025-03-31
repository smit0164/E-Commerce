@extends('layouts.admin.app')
@section('title', 'Product Management')
@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto ">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Manage Products</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.products.trashed') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded flex items-center">
                <i class="fas fa-trash-restore mr-2"></i> View Trashed Products
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Products</label>
                <div class="relative">
                    <input type="text" 
                           id="search-product" 
                           name="search"
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                           placeholder="Search by name or category...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Price Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                <div class="flex space-x-2">
                    <input type="number" 
                           id="price_min-product" 
                           name="price_min"
                           placeholder="Min" 
                           min="0"
                           class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="number" 
                           id="price_max-product" 
                           name="price_max"
                           placeholder="Max" 
                           min="0"
                           class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <div class="flex space-x-2">
                    <input type="date" 
                           id="date_start-product" 
                           name="date_start"
                           class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="date" 
                           id="date_end-product" 
                           name="date_end"
                           class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>
    </div>

    <div id="product-table-container">
        @include('pages.admin.products.partials.product_table')
    </div>
    <div class="mt-6" id="pagination-product">
        {{ $products->links('pagination::simple-tailwind') }}
    </div>
  
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal-product" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Move Product to Trash</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to move the product "<span id="delete-product-name"></span>" to trash? You can restore it later from the trash.</p>
                </div>
                <form id="delete-product-form" action="" method="POST" class="mt-4">
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

<!-- Inline JavaScript -->
<script>
    function openDeleteModal(productSlug, productName) {
        const modal = document.getElementById('delete-modal-product');
        const productNameSpan = document.getElementById('delete-product-name');
        const deleteForm = document.getElementById('delete-product-form');

        productNameSpan.textContent = productName;
        deleteForm.action = "{{ route('admin.products.destroy', '') }}/" + productSlug;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal-product').classList.add('hidden');
    }

    document.getElementById('delete-modal-product').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });


    $(document).ready(function () {
        function fetchProducts(page = 1) {
            let search = $('#search-product').val();
            let priceMin = $('#price_min-product').val();
            let priceMax = $('#price_max-product').val();
            let dateStart = $('#date_start-product').val();
            let dateEnd = $('#date_end-product').val();

            $.ajax({
                url: "{{ route('admin.products.index') }}",
                method: "GET",
                data: {
                    search: search,
                    price_min: priceMin,
                    price_max: priceMax,
                    date_start: dateStart,
                    date_end: dateEnd,
                    page: page
                },
                success: function (response) {
                    $("#product-table-container").html(response.html);

                    // Update pagination links
                    $('#pagination-product').html(response.pagination);
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }



    // Add event listeners
    $('#search-product,#price_min-product,#price_max-product,#date_start-product,#date_end-product').on('input',function(){
        fetchProducts();
    });
    $(document).on('click', '#pagination-product a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = new URL(url).searchParams.get('page');
        fetchProducts(page);
    });
});

</script>
@endsection