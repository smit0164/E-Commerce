@extends('layouts.admin.app')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Order Details</h2>

        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Order #{{ $order->id }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Details -->
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold text-gray-700">Customer Information</h3>
                    <p class="mt-2"><strong>Name:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                </div>

                <!-- Order Details -->
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold text-gray-700">Order Summary</h3>
                    <p class="mt-2"><strong>Status:</strong> 
                        <span class="px-3 py-1 text-white text-sm font-medium rounded-full 
                            @if($order->status == 'pending') bg-yellow-500 
                            @elseif($order->status == 'shipped') bg-blue-500 
                            @elseif($order->status == 'delivered') bg-green-500 
                            @else bg-red-500 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
                    <p><strong>Created At:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <!-- Shipping & Billing Address -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold text-gray-700">Shipping Address</h3>
                    <p class="mt-2"><strong>Name:</strong> {{ $order->shippingAddress->full_name }}</p>
                    <p><strong>Address:</strong> {{ $order->shippingAddress->address_line1 }}</p>
                    <p><strong>City:</strong> {{ $order->shippingAddress->city }}</p>
                    <p><strong>State:</strong> {{ $order->shippingAddress->state }}</p>
                    <p><strong>ZIP Code:</strong> {{ $order->shippingAddress->postal_code }}</p>
                    <p><strong>Country:</strong> {{ $order->shippingAddress->country }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-lg font-semibold text-gray-700">Billing Address</h3>
                    <p class="mt-2"><strong>Name:</strong> {{ $order->billingAddress->full_name }}</p>
                    <p><strong>Address:</strong> {{ $order->billingAddress->address_line1 }}</p>
                    <p><strong>City:</strong> {{ $order->billingAddress->city }}</p>
                    <p><strong>State:</strong> {{ $order->billingAddress->state }}</p>
                    <p><strong>ZIP Code:</strong> {{ $order->billingAddress->postal_code }}</p>
                    <p><strong>Country:</strong> {{ $order->billingAddress->country }}</p>
                </div>
            </div>

            <!-- Order Items -->
            <h3 class="text-lg font-semibold mt-8 text-gray-700">Order Items</h3>
            <div class="overflow-x-auto mt-4">
                <table class="w-full border border-gray-200 bg-white shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-left">
                            <th class="py-2 px-4 border">Product</th>
                            <th class="py-2 px-4 border">Quantity</th>
                            <th class="py-2 px-4 border">Unit Price</th>
                            <th class="py-2 px-4 border">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr class="border">
                                <td class="py-2 px-4 border flex items-center">
                                    <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                        alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg mr-3">
                                    <span>{{ $item->product->name }}</span>
                                </td>
                                <td class="py-2 px-4 border">{{ $item->quantity }}</td>
                                <td class="py-2 px-4 border">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-2 px-4 border font-semibold">₹{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('admin.orders.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-lg transition">
                    Back to Orders
                </a>
            </div>
        </div>
    </div>
@endsection
