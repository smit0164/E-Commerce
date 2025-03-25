@props(['category'])

<div class="relative group overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300">
    <!-- Image -->
    <img src="{{ asset('storage/categories/' . $category->image) }}" 
         alt="{{ $category->name }}"
         class="w-full h-56 object-cover transform transition-transform duration-500 group-hover:scale-110">

    <!-- Overlay and Text -->
    <a href="{{ route('category.products', $category->slug) }}"
       class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent flex items-end justify-center p-4 text-white transition-all duration-300 group-hover:bg-opacity-70">
        <span class="text-lg md:text-xl font-semibold uppercase tracking-wide transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            {{ $category->name }}
        </span>
    </a>
</div>