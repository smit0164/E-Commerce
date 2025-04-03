@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto p-6 my-0">
        <h1 class="text-2xl font-bold text-gray-900 my-0">Admin Dashboard</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            
           <x-admin.stats-card title="Total Orders" :value="$totalOrders" icon="fa-shopping-cart" color="bg-indigo-100 text-indigo-600" />
            
            
                <x-admin.stats-card title="Total Revenue" :value="'₹ ' . number_format($totalRevenue, 2)" icon="fa-indian-rupee-sign" color="bg-green-100 text-green-600" />
           
            
                <x-admin.stats-card title="Total Customers" :value="$totalCustomers" icon="fa-users" color="bg-blue-100 text-blue-600" />
          
           
                <x-admin.stats-card title="Pending Orders" :value="$pendingOrders" icon="fa-spinner" color="bg-orange-100 text-orange-600" />
        
          
                <x-admin.stats-card title="Shipped Orders" :value="$shippedOrders" icon="fa-truck" color="bg-purple-100 text-purple-600" />
            
           
                <x-admin.stats-card title="Delivered Orders" :value="$deliveredOrders" icon="fa-check-circle" color="bg-teal-100 text-teal-600" />
        
        </div>

        <!-- Latest Orders -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Latest Orders</h2>
                <!-- View All Orders Button -->
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                    View All Orders
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase">Order ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase">Customer</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase">Total</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($latestOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 font-medium">#{{ $order->id }}</td>
                                <td class="px-3 py-3">{{ $order->customer->name ?? 'Guest' }}</td>
                                <td class="px-3 py-3">₹{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-3 py-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-orange-600',
                                            'shipped' => 'bg-purple-600',
                                            'delivered' => 'bg-green-600',
                                        ];
                                        $status = strtolower($order->status);
                                        $statusClass = $statusColors[$status] ?? 'bg-gray-600';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-medium text-white {{ $statusClass }} rounded">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-3 text-center text-gray-500">No recent orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection