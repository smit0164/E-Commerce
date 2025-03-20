<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <div class="text-lg font-semibold text-gray-800">E-commerce Admin</div>

    <div class="flex items-center space-x-4">
        @if(Auth::guard('admin')->check())
            <span class="text-gray-700 font-medium">{{ Auth::guard('admin')->user()->email }}</span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 transition">
                    Logout
                </button>
            </form>
        @endif
    </div>
</header>
