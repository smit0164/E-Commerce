@props(['id', 'image', 'name', 'availability', 'quantity', 'price'])

<div class="cart-item flex items-center justify-between bg-white border border-gray-200 p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
    <div class="flex items-center">
        <img src="{{ asset('storage/products/' . $image) }}"  alt="{{ $name }}" class="w-16 h-16 rounded-lg object-cover">
        <div class="ml-4">
            <h3 class="text-sm font-semibold text-gray-800">{{ $name }}</h3>
            <p class="text-xs text-gray-500">Availability: 
                <span class="text-green-500 font-medium">{{ $availability }}</span>
            </p>
        </div>
    </div>
    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <button class="update-quantity bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300 text-gray-800 font-semibold transition-all duration-200"
                data-product-id="{{ $id }}" data-action="decrease">−</button>

            <input type="number" value="{{ $quantity }}" class="cart-quantity w-12 border border-gray-300 text-center rounded-md px-1 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                data-product-id="{{ $id }}">

            <button class="update-quantity bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300 text-gray-800 font-semibold transition-all duration-200"
                data-product-id="{{ $id }}" data-action="increase">+</button>
        </div>
        <div class="text-red-500 font-bold text-lg">₹{{ number_format($price, 2) }}</div>
        <button class="remove-from-cart text-gray-500 hover:text-red-600 transition-all duration-200 text-xl"
            data-product-id="{{ $id }}">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</div>
