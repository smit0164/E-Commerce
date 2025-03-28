@extends('layouts.users.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-[280px,1fr] gap-8">
            <!-- Sidebar -->
            <aside class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Categories</h3>
                <div class="space-y-3">
                    @foreach ($categories as $category)
                        <div class="flex items-center group form-check">
                            <input type="checkbox" value="{{ $category->id }}"
                                id="category-{{ $category->id }}"
                                class="text-primary focus:ring-2 focus:ring-primary/20 rounded-sm cursor-pointer transition-colors form-check-input category-filter">
                            <label for="category-{{ $category->id }}"
                                class="text-gray-600 ml-3 cursor-pointer group-hover:text-gray-800 transition-colors flex-1">
                                {{ $category->name }}
                            </label>
                            <span class="ml-auto text-gray-500 text-sm">({{ $category->products_count }})</span>
                        </div>
                    @endforeach
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-5 uppercase tracking-wide">Price Range</h3>
                <div class="grid grid-cols-2 gap-3">
                    <input type="number" name="price_min" id="price_min"
                        class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400"
                        placeholder="Min" min="0" step="0.01" aria-label="Minimum Price">
                    <input type="number" name="price_max" id="price_max"
                        class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400"
                        placeholder="Max" min="0" step="0.01" aria-label="Maximum Price">
                </div>
            </aside>

            <!-- Products Section -->
            <section class="pb-8">
                <div id="loading" class="hidden text-center py-4">Loading...</div>
                <div id="product-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('pages.customer.products.partials.product_grid')
                </div>
                <div id="pagination" class="mt-8">
                    {{ $products->links('pagination::simple-tailwind') }}
                </div>
            </section>
        </div>
    </div>

    <script>
    $(document).ready(function() {

        function applyFilters(page = 1) {
            $('#loading').removeClass('hidden');
            let categories = [];
            $('.category-filter:checked').each(function() {
                categories.push($(this).val());
            });
            let priceMin = $('#price_min').val();
            let priceMax = $('#price_max').val();
            let url = "{{ route('products.index') }}?page=" + page;
            if (categories.length) url += "&categories=" + categories.join(",");
            if (priceMin) url += "&price_min=" + priceMin;
            if (priceMax) url += "&price_max=" + priceMax;
            window.history.pushState({}, document.title, url);
            $.ajax({
                url: "{{ route('products.filter') }}",
                method: 'POST',
                data: {
                    categories: categories,
                    price_min: priceMin,
                    price_max: priceMax,
                    page: page, // Include the page number
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#product-grid').html(response.html);
                    $('#pagination').html(response.pagination);
                    $('#loading').addClass('hidden');
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    $('#loading').addClass('hidden');
                }
            });
        }

        
        $('.category-filter, #price_min, #price_max').on('change',  function(){applyFilters()});
        $(document).on('click', '#pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let page = new URL(url).searchParams.get('page');
            applyFilters(page);
        });
        function setInitialFilterState() {
            const urlParams = new URLSearchParams(window.location.search);
            console.log(urlParams);


            const categories = urlParams.get('categories') ? urlParams.get('categories').split(',') : [];
            const priceMin = urlParams.get('price_min') || '';
            const priceMax = urlParams.get('price_max') || '';

            // Set checkboxes
            $('.category-filter').each(function() {
                $(this).prop('checked', categories.includes($(this).val()));
            });

            // Set price inputs
            $('#price_min').val(priceMin);
            $('#price_max').val(priceMax);
        }
        setInitialFilterState();
    });
    </script>
@endsection