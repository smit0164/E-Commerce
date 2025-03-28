<header class="py-3 shadow-sm bg-white sticky top-0 z-50">
    <div class="container mx-auto px-6 flex items-center justify-between gap-8">
        <!-- Logo -->
        <x-users.logo/>

        <!-- Search Bar -->
        <div class="flex-grow max-w-2xl relative">
            <form class="relative flex items-center" action="{{ url('/search') }}" method="GET" id="search-form">
                <label for="search" class="sr-only">Search products</label>
                <input type="text" name="search" id="search"
                    class="w-full border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 px-10 py-2.5 rounded-full shadow-sm focus:outline-none text-gray-700 transition-all duration-200"
                    placeholder="Search for products..." aria-label="Search for products">
                <button type="button"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-base hover:text-primary transition-colors"
                    aria-label="Search icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button type="submit"
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 bg-primary text-white px-5 py-1.5 rounded-full hover:bg-primary/90 transition-all duration-200">
                    Search
                </button>

                <!-- Search Modal -->
                <div id="search-modal" class="absolute left-0 top-full mt-2 w-full bg-white shadow-lg rounded-md z-50 hidden">
                    <div id="search-results" class="py-2 max-h-64 overflow-y-auto">
                        <!-- Products will be injected here via AJAX -->
                    </div>
                    <div id="view-more" class="border-t border-gray-200 py-2 text-center hidden">
                        <a href="#" class="text-primary hover:underline">View More</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-5">
            <!-- Contact Info -->
            <div class="hidden lg:flex flex-col items-end gap-1 text-gray-700 text-left">
                <a href="tel:+919265601854"
                    class="font-semibold text-xs flex items-center gap-1 hover:text-primary transition-colors ltr">
                    <i class="fas fa-phone-alt fa-flip-horizontal"></i>
                    +91 9265601854
                </a>
                <a href="mailto:smitpatel.ast@gmail.com"
                    class="font-semibold text-xs flex items-center gap-1 hover:text-primary transition-colors ltr">
                    <i class="fas fa-envelope"></i> smitpatel.ast@gmail.com
                </a>
            </div>

            <!-- User Actions -->
            <nav class="flex items-center gap-6" aria-label="User navigation">
                <!-- Cart -->
                <a href="{{ route('cart.index') }}"
                    class="group text-gray-700 hover:text-primary transition-colors relative flex flex-col items-center">
                    <div class="text-xl">
                        <i class="fa-solid fa-bag-shopping group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span class="text-[10px] mt-0.5">Cart</span>
                    <span id="cart-count"
                        class="absolute -right-2.5 -top-0.5 w-4 h-4 rounded-full flex items-center justify-center bg-primary text-white text-[9px]">
                        {{ app(\App\Services\Cart::class)->totalItems() }}
                    </span>
                </a>

                <!-- Account -->
                @auth('customer')
                    <div class="relative">
                        <button id="profileButton" class="flex items-center text-gray-700 hover:text-red-500 transition-colors">
                            <div class="text-xl mr-2">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <span class="text-sm font-medium">{{ Auth::guard('customer')->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div id="profileDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 hidden transition-opacity duration-300 z-50">
                            <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('show.order.history', ['userid' => Auth::guard('customer')->user()->id]) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('register') }}"
                        class="group text-gray-700 hover:text-red-500 transition-colors flex flex-col items-center">
                        <div class="text-xl">
                            <i class="fa-regular fa-user group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span class="text-[10px] mt-0.5">Register</span>
                    </a>
                @endauth
            </nav>
        </div>
    </div>
</header>

<!-- JavaScript for Search (Add at the bottom of the layout or in a separate script file) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let searchTimeout;

    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        let searchTerm = $(this).val().trim();

        if (searchTerm.length > 0) {
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: "{{ route('products.search') }}", // New route for AJAX search
                    method: 'GET',
                    data: { search: searchTerm },
                    success: function(response) {
                        $('#search-results').html(response.html);
                        $('#view-more').toggle(response.hasMore);
                        $('#view-more a').attr('href', "{{ route('products.index') }}?search=" + encodeURIComponent(searchTerm));
                        $('#search-modal').removeClass('hidden');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        $('#search-modal').addClass('hidden');
                    }
                });
            }, 300); // Debounce to avoid too many requests
        } else {
            $('#search-modal').addClass('hidden');
        }
    });

    // Hide modal when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search-form').length) {
            $('#search-modal').addClass('hidden');
        }
    });

    // Prevent form submission from reloading (optional, since we use AJAX)
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        let searchTerm = $('#search').val().trim();
        if (searchTerm) {
            window.location.href = "{{ route('products.index') }}?search=" + encodeURIComponent(searchTerm);
        }
    });
});
</script>