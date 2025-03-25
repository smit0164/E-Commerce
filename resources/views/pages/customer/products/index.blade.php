@extends('layouts.users.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <form id="filterForm" method="GET" action="{{ route('products.index') }}"
            class="grid grid-cols-1 md:grid-cols-[280px,1fr] gap-8">
            <!-- Sidebar -->
            <aside class="bg-white p-6 rounded-lg shadow-md">
                <!-- Categories -->
                <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Categories</h3>
                <div class="space-y-3">
                    @foreach ($categories as $category)
                        <div class="flex items-center group">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                id="category-{{ $category->id }}"
                                class="text-primary focus:ring-2 focus:ring-primary/20 rounded-sm cursor-pointer transition-colors"
                                {{ in_array($category->id, (array) request('categories', [])) ? 'checked' : '' }}>
                            <label for="category-{{ $category->id }}"
                                class="text-gray-600 ml-3 cursor-pointer group-hover:text-gray-800 transition-colors flex-1">
                                {{ $category->name }}
                            </label>
                            <span class="ml-auto text-gray-500 text-sm">({{ $category->products_count }})</span>
                        </div>
                    @endforeach
                </div>

                <!-- Price Filter -->
                <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-5 uppercase tracking-wide">Price Range</h3>
                <div class="grid grid-cols-2 gap-3">
                    <input type="number" name="min" id="min" value="{{ request('min') }}"
                        class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400"
                        placeholder="Min" min="0" step="0.01" aria-label="Minimum Price">
                    <input type="number" name="max" id="max" value="{{ request('max') }}"
                        class="w-full border-gray-300 focus:border-primary rounded-lg focus:ring-2 focus:ring-primary/20 px-3 py-2 shadow-sm placeholder-gray-400"
                        placeholder="Max" min="0" step="0.01" aria-label="Maximum Price">
                </div>
                <div class="mt-4 flex space-x-3">
                    <button type="submit"
                        class="flex-1 bg-primary text-white py-2 rounded-lg hover:bg-primary/90 transition-colors">
                        Apply Filters
                    </button>
                    <a href="{{ route('products.index') }}"
                        class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center">
                        Clear Filters
                    </a>
                </div>
            </aside>

            <!-- Products Section -->
            <section class="pb-8">
                <!-- Responsive Product Grid -->
                @if ($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            <x-users.product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links('pagination::simple-tailwind') }} <!-- Changed to simple-tailwind -->
                    </div>
                @else
                    <p class="text-gray-600 text-center">No products found matching your filters.</p>
                @endif
            </section>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('filterForm');
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            const priceInputs = form.querySelectorAll('input[type="number"]');
            let debounceTimeout;

            // Auto-submit on checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    form.submit();
                });
            });

            // Debounce price input changes
            priceInputs.forEach(input => {
                input.addEventListener('input', () => {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        form.submit();
                    }, 500); // 500ms delay
                });
            });
        });
    </script>
@endsection