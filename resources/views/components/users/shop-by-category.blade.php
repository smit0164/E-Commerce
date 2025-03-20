@props(['categories'])

<div class="container py-12">
    <h2 class="text-3xl font-semibold text-gray-800 uppercase mb-8 text-center">Shop by Category</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($categories as $category)
            <div class="relative group overflow-hidden rounded-lg shadow-lg">
                <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}"
                    class="w-full h-56 object-cover transform transition-transform duration-300 group-hover:scale-105">

                    <a href="{{ route('category.products', $category->slug) }}"
                        class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center text-xl text-white font-semibold tracking-wide uppercase transition duration-300 group-hover:bg-opacity-60">
                        {{ $category->name }}
                    </a>
                    
            </div>
        @endforeach
    </div>
</div>
