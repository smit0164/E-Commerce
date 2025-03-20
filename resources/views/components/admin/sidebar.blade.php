<!-- resources/views/components/admin/sidebar.blade.php -->
<aside class="w-64 bg-white shadow-md p-6 flex flex-col">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Admin Panel</h2>
    <nav class="space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 transition">
            <i class="fas fa-tachometer-alt w-5 h-5"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.products') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 transition">
            <i class="fas fa-box w-5 h-5"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('admin.orders') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 transition">
            <i class="fas fa-shopping-cart w-5 h-5"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 transition">
            <i class="fas fa-folder w-5 h-5"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 transition">
            <i class="fas fa-users w-5 h-5"></i>
            <span>Users</span>
        </a>
    </nav>
</aside>