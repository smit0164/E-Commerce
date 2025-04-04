<aside class="w-64 h-screen bg-white shadow-lg p-6 flex flex-col">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Panel</h2>
    <nav class="space-y-2">
        @php
            $currentRoute = Route::currentRouteName();
        @endphp
        @can('manage-dashboard')
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.dashboard' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-tachometer-alt w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
        @endcan

        @can('manage-orders')
            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.orders.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-shopping-cart w-5 h-5"></i>
                <span>Orders</span>
            </a>
        @endcan

        @can('manage-products')
            <a href="{{ route('admin.products.index') }}" 
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.products.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-box w-5 h-5"></i>
                <span>Products</span>
            </a>
        @endcan
 
        @can('manage-categories')
            <a href="{{ route('admin.categories.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.categories.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-folder w-5 h-5"></i>
                <span>Categories</span>
            </a>
        @endcan
        
        @can('manage-static-blocks')
            <a href="{{ route('admin.static_blocks.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.static_blocks.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-cogs w-5 h-5"></i>
                <span>Static Blocks</span>
            </a>
        @endcan
        
        @can('manage-static-page')
            <a href="{{ route('admin.static_pages.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.static_pages.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-file-alt w-5 h-5"></i>
                <span>Static Pages</span>
            </a>
        @endcan
        
        @can('manage-users')
        <a href="{{ route('admin.admins.index') }}"
            class="flex items-center space-x-3 p-3 rounded-lg transition 
            {{ $currentRoute == 'admin.admins.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
            <i class="fas fa-user-tie w-5 h-5"></i>
            <span>Admins</span>
        </a>
        @endcan
       
        @can('manage-users')
            <a href="{{ route('admin.roles.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition 
                {{ $currentRoute == 'admin.roles.index' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="fas fa-user-shield w-5 h-5"></i>
                <span>Roles</span>
            </a>
        @endcan

    </nav>
</aside>
