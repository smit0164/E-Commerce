@extends('layouts.admin.app')

@section('title', 'Order Management')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center justify-between">
        Manage Orders
    </h2>

    <!-- Success/Error Messages -->
  
    
    <!-- Orders Table -->
    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-gray-700">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-5 font-medium">#{{ $order->id }}</td>
                        <td class="px-6 py-5">{{ $order->customer->name }}</td>
                        <td class="px-6 py-5">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white 
                                @if($order->status == 'pending') bg-yellow-500 
                                @elseif($order->status == 'shipped') bg-sky-500 
                                @elseif($order->status == 'delivered') bg-green-500 
                                @endif rounded-full">
                                <i class="fas fa-circle mr-1.5"></i> {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-5">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-5 flex space-x-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-800 transition" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Edit Status">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
      
        <div class="mt-2">
            {{ $orders->links('pagination::simple-tailwind') }}
        </div>
    
</div>
<script>

    // Function to show Toastr notifications
    function showNotifications() {
        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif
    }

    // Run notifications after DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof toastr !== 'undefined') {
            showNotifications();
        } else {
            console.error('Toastr is not loaded');
        }
    });
</script>
@endsection