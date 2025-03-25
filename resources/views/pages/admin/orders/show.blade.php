@extends('layouts.admin.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto mt-6">
<div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Order #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.edit', $order->id) }}" 
           class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm shadow-sm hover:shadow-md">
            Edit Order
        </a>
    </div>
    <div class="space-y-6">
        <!-- Customer Details & Order Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Details -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Customer Information</h3>
                @if ($order->customer)
                    <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'Not provided' }}</p>
                @else
                    <p class="text-gray-500">Customer information not available.</p>
                @endif
            </div>

            <!-- Order Details -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Order Summary</h3>
                <p><strong>Status:</strong>
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white rounded-full 
                        @if($order->status == 'pending') bg-yellow-500 
                        @elseif($order->status == 'processing') bg-orange-500 
                        @elseif($order->status == 'shipped') bg-blue-500 
                        @elseif($order->status == 'delivered') bg-green-500 
                        @elseif($order->status == 'canceled') bg-red-500 
                        @else bg-gray-500 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Created At:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <!-- Shipping & Billing Address -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Shipping Address -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Shipping Address</h3>
                @if ($order->shippingAddress)
                    <p><strong>Name:</strong> {{ $order->shippingAddress->full_name }}</p>
                    <p><strong>Address:</strong> {{ $order->shippingAddress->address_line1 }}</p>
                    <p><strong>City:</strong> {{ $order->shippingAddress->city }}</p>
                    <p><strong>State:</strong> {{ $order->shippingAddress->state }}</p>
                    <p><strong>ZIP Code:</strong> {{ $order->shippingAddress->postal_code }}</p>
                    <p><strong>Country:</strong> {{ $order->shippingAddress->country }}</p>
                @else
                    <p class="text-gray-500">Shipping address not available.</p>
                @endif
            </div>

            <!-- Billing Address -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Billing Address</h3>
                @if ($order->billingAddress)
                    <p><strong>Name:</strong> {{ $order->billingAddress->full_name }}</p>
                    <p><strong>Address:</strong> {{ $order->billingAddress->address_line1 }}</p>
                    <p><strong>City:</strong> {{ $order->billingAddress->city }}</p>
                    <p><strong>State:</strong> {{ $order->billingAddress->state }}</p>
                    <p><strong>ZIP Code:</strong> {{ $order->billingAddress->postal_code }}</p>
                    <p><strong>Country:</strong> {{ $order->billingAddress->country }}</p>
                @else
                    <p class="text-gray-500">Billing address not available.</p>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Order Items</h3>
            <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="py-2 px-4 text-left text-xs font-semibold uppercase tracking-wider">Product</th>
                            <th class="py-2 px-4 text-left text-xs font-semibold uppercase tracking-wider">Quantity</th>
                            <th class="py-2 px-4 text-left text-xs font-semibold uppercase tracking-wider">Unit Price</th>
                            <th class="py-2 px-4 text-left text-xs font-semibold uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($order->orderItems as $item)
                        <tr>
                            <td class="py-2 px-4">{{ $item->product_name}}</td>
                            <td class="py-2 px-4">{{ $item->quantity }}</td>
                            <td class="py-2 px-4">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-2 px-4 font-semibold">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                         @empty
                        <tr>
                            <td colspan="4" class="py-2 px-4 text-center text-gray-500">No items in this order.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.orders.index') }}" 
               class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition duration-200 text-sm shadow-sm hover:shadow-md">
                Back to Orders
            </a>
        </div>
    </div>
</div>
@endsection