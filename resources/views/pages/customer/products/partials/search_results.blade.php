@foreach ($products as $product)
    <a href="{{ route('products.show', $product->slug) }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center">
        <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover mr-3">
        <div>
            <span class="text-gray-700">{{ $product->name }}</span>
            <span class="block text-gray-500 text-sm">${{ number_format($product->price, 2) }}</span>
        </div>
    </a>
@endforeach
