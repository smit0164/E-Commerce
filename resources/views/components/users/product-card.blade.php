@props(['product'])

<div class="bg-white shadow-lg rounded-xl overflow-hidden flex flex-col h-full transition-shadow hover:shadow-2xl">
    <!-- Image Section -->
    <a href="{{ url('products/' . $product->slug) }}" class="block relative">
        <img src="{{ asset('storage/products/' . $product->image) }}" 
             alt="{{ $product->name }}" 
             class="w-full h-56 object-cover transition-transform hover:scale-105">
    </a>

    <!-- Content Section -->
    <div class="p-5 flex-grow">
        <h4 class="uppercase font-semibold text-lg text-gray-800 tracking-wide line-clamp-1 hover:text-primary transition-colors">
            {{ $product->name }}
        </h4>
        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
            {{ $product->description }}
        </p>
        <div class="flex items-center justify-between mb-3">
            <p class="text-2xl text-primary font-bold">₹{{ number_format($product->price, 2) }}</p>
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                {{ $product->quantity > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                {{ $product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
            </span>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="px-5 pb-5">
        <button data-cart-action="add" 
                data-product-id="{{ $product->id }}"
                class="w-full flex items-center justify-center gap-2 px-6 py-2 font-medium text-white uppercase rounded-lg 
                {{ $product->quantity > 0 ? 'bg-primary hover:bg-transparent hover:text-primary border border-primary transition-all' : 'bg-gray-400 opacity-50 cursor-not-allowed' }}"
                {{ $product->quantity == 0 ? 'disabled' : '' }}>
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Add to Cart</span>
            <i class="fa-solid fa-spinner fa-spin hidden"></i>
        </button>
    </div>
</div>