@props(['categories'])
<nav class="bg-gray-800">
    <div class="container flex items-center">
        <!-- Categories Dropdown (Hidden on small screens) -->
        <div class="px-8 py-4 bg-primary items-center cursor-pointer relative group hidden md:block">
            <span class="text-white">
                <i class="fa-solid fa-bars"></i>
            </span>
            <span class="capitalize ml-2 text-white">All Categories</span>

            <!-- Dropdown -->
            <div class="absolute w-full left-0 top-full bg-white shadow-md py-3 divide-y divide-gray-300 divide-dashed opacity-0 group-hover:opacity-100 transition duration-300 invisible group-hover:visible">
                @foreach ($categories as $category)
                    <a href="{{ route('category.products', $category->slug) }}" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Navigation Links -->
        <div class="flex items-center justify-between flex-grow md:pl-12 py-5">
            <div class="flex items-center space-x-6 capitalize">
                <a href="/" class="text-gray-200 hover:text-white transition">Home</a>
                <a href="{{ route('products.index') }}" class="text-gray-200 hover:text-white transition">Shop</a>
                <a href="/contact" class="text-gray-200 hover:text-white transition">Contact Us</a>
            </div>

            @guest('customer')
                <a href="{{ url('/login') }}" class="text-gray-200 hover:text-white font-medium transition">Login</a>
            @endguest
        </div>
    </div>
</nav>
