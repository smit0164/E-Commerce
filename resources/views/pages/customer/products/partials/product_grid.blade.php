@if ($products->count()>0)
    @foreach ($products as $product)
        <x-users.product-card :product="$product" />
    @endforeach
@else
    <p class="text-gray-600 text-center">No products found matching your filters.</p>
@endif