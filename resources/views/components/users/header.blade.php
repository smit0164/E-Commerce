<header class="py-3 shadow-sm bg-white sticky top-0 z-50">
    <div class="container mx-auto px-6 flex items-center justify-between gap-5">
        <!-- Contact Information -->
      

        <!-- Logo -->
        <x-users.logo />
        
        <!-- Search Bar -->
        <div class="flex-grow max-w-2xl relative">
            <form class="relative flex items-center" action="{{ route('products.search') }}" id="search-form">
                <label for="search" class="sr-only">Search products</label>
                <input type="text" name="search" id="search"
                    class="w-full border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 px-10 py-2.5 rounded-full shadow-sm focus:outline-none text-gray-700 transition-all duration-200"
                    placeholder="Search for products..." aria-label="Search for products" value="{{ request('search') }}">
                <button type="submit"
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 bg-primary text-white px-5 py-1.5 rounded-full hover:bg-primary/90 transition-all duration-200">
                    Search
                </button>
            </form>

            <!-- Search Modal -->
            <div id="search-modal"
                class="absolute left-0 top-full mt-2 w-full bg-white shadow-lg rounded-md z-50 hidden">
                <div id="search-results" class="py-2 max-h-64 overflow-y-auto">
                    <!-- Products will be injected here via AJAX -->
                </div>
                <div id="view-more" class="border-t border-gray-200 py-2 text-center hidden">
                    <a href="{{ route('products.search') }}?search=" id="view-more-link"
                        class="text-primary hover:underline">View More</a>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="hidden md:flex flex-col items-end text-gray-600 text-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-phone text-primary"></i> 
                <a href="tel:+919265601854" class="hover:text-primary">+91 9265601854</a>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-envelope text-primary"></i> 
                <a href="mailto:support@example.com" class="hover:text-primary">support@example.com</a>
            </div>
        </div>
        



        <!-- Right Section -->
        <div class="flex items-center gap-5">
            <!-- User Actions -->
            <nav class="flex items-center gap-6">
                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="group text-gray-700 hover:text-primary transition-colors relative">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                    <span class="absolute -right-2.5 -top-0.5 w-4 h-4 rounded-full flex items-center justify-center bg-primary text-white text-[9px]" id="cart-count">
                        {{ app(\App\Services\Cart::class)->totalItems() }}
                    </span>
                </a>

                <!-- Account -->
                @auth('customer')
                    <div class="relative">
                        <button id="profileButton" class="flex items-center text-gray-700 hover:text-red-500 transition-colors">
                            <i class="fa-regular fa-user text-xl mr-2"></i>
                            <span class="text-sm font-medium">{{ Auth::guard('customer')->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 hidden">
                            <a href="{{ route('customer.profile')}}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('show.order.history', ['userid' => Auth::guard('customer')->user()->id]) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-red-500 transition-colors flex flex-col items-center">
                        <i class="fa-regular fa-user text-xl"></i>
                        <span class="text-[10px] mt-0.5">Register</span>
                    </a>
                @endauth
            </nav>
        </div>
    </div>
</header>

<script>
    $(document).ready(function() {
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            let searchTerm = $(this).val().trim();

            if (searchTerm.length >= 1) {
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('products.search') }}",
                        method: 'GET',
                        data: { search: searchTerm },
                        success: function(response) {
                            if (!response.html) {
                                $('#search-results').html("<p class='text-gray-500 p-2'>No products found</p>");
                            } else {
                                $('#search-results').html(response.html);
                                $('#view-more-link').attr('href', "{{ route('products.search') }}?search=" + encodeURIComponent(searchTerm));
                                $('#view-more').toggle(response.hasMore);
                                $('#search-modal').removeClass('hidden');
                            }
                        },
                        error: function() {
                            $('#search-modal').addClass('hidden');
                        }
                    });
                }, 300);
            } else {
                $('#search-modal').addClass('hidden');
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#search-form').length) {
                $('#search-modal').addClass('hidden');
            }
        });
        
    });
</script>
