<header class="py-3 shadow-sm bg-white sticky top-0 z-50">
    <div class="container mx-auto px-6 flex items-center justify-between gap-8">
        <!-- Logo -->
        <x-users.logo/>

        <!-- Search Bar -->
        <div class="flex-grow max-w-2xl relative">
            <form class="relative flex items-center" action="{{ url('/search') }}" method="GET">
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
                <a href="/cart"
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

                        <!-- Profile Dropdown -->
                        <div id="profileDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 hidden transition-opacity duration-300 z-50">
                            <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Orders</a>
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
