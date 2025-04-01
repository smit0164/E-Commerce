@extends('layouts.users.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-[280px,1fr] gap-8">
        <!-- Sidebar -->
        <aside class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Categories</h3>
            <div class="space-y-3">
                @foreach ($allCategories as $category)  <!-- Use $allCategories to iterate through all categories -->
                <div class="flex items-center group form-check">
                    <input type="checkbox" value="{{ $category->slug }}" 
                           id="category-{{ $category->slug }}" 
                           class="text-primary focus:ring-2 focus:ring-primary/20 rounded-sm cursor-pointer transition-colors form-check-input category-filter"
                           @if(in_array($category->slug, (array)explode(',', request()->categories ?? ''))) checked @endif> <!-- Ensure categories are treated as an array -->
                    <label for="category-{{ $category->slug }}" class="text-gray-600 ml-3 cursor-pointer group-hover:text-gray-800 transition-colors flex-1">
                        {{ $category->name }}
                    </label>
                    <span class="ml-auto text-gray-500 text-sm">({{ $category->products_count }})</span>
                </div>
                @endforeach
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-5 uppercase tracking-wide">Price Range</h3>
            <div class="grid grid-cols-2 gap-3">
                <input type="number" name="price_min" id="price_min" class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400" placeholder="Min" min="0" step="0.01" aria-label="Minimum Price" value="{{ request()->price_min }}">
                <input type="number" name="price_max" id="price_max" class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400" placeholder="Max" min="0" step="0.01" aria-label="Maximum Price" value="{{ request()->price_max }}">
            </div>
        </aside>

        <!-- Products Section -->
        <section class="pb-8">
            <div id="loading" class="hidden text-center py-4">Loading...</div>
            <div id="product-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('pages.customer.products.partials.product_grid') <!-- Initial Product Grid -->
            </div>
            <div id="pagination" class="mt-8">
                {{ $products->links('pagination::simple-tailwind') }} <!-- Initial Pagination -->
            </div>
        </section>
    </div>
</div>

<script>
$(document).ready(function() {
    function updateFilters(page = 1) {
        let categories = [];
        $('.category-filter:checked').each(function() {
            categories.push($(this).val());
        });
        let priceMin = $('#price_min').val();
        let priceMax = $('#price_max').val();

        // Show loading spinner
        $('#loading').removeClass('hidden');

        // Send the AJAX request to the index route
        $.ajax({
            url: "{{ route('products.index') }}", // Correct route to index
            method: 'GET',
            data: {
                categories: categories.join(','),  // Categories selected in the filter
                price_min: priceMin,  // Minimum price
                price_max: priceMax,  // Maximum price
                page: page,  // Current page number
            },
            success: function(response) {
                // Update the product grid with the new HTML
                $('#product-grid').html(response.html);
                // Update the pagination links
                $('#pagination').html(response.pagination);
                // Hide the loading spinner
                $('#loading').addClass('hidden');

                // Update the URL with the selected filters without reloading the page
                let url = new URL(window.location);
                url.searchParams.set('categories', categories.join(','));
                url.searchParams.set('price_min', priceMin);
                url.searchParams.set('price_max', priceMax);
                url.searchParams.set('page', page);
                history.pushState(null, '', url); // Update URL
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                // Hide the loading spinner if there's an error
                $('#loading').addClass('hidden');
            }
        });
        $(document).on('click', '#pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let page = new URL(url).searchParams.get('page');
            updateFilters(page); // Call the function with the selected page number
        });
    }

    // Event listener for changes in the filters
    $('.category-filter, #price_min, #price_max').on('change', function() {
        updateFilters(); // Call the function to apply filters
    });

    // Event listener for pagination links
  
});
</script>
@endsection
