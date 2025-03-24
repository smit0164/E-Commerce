@extends('layouts.admin.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Update Order Status</h2>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold">Order #{{ $order->id }}</h3>
        <p><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
        <p><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>

        <p><strong>Current Status:</strong> 
            <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded">{{ ucfirst($order->status) }}</span>
        </p>

        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="mt-4">
            @csrf
            @method('PUT')
            <label for="status" class="block font-semibold mb-2">Change Status:</label>
            <select name="status" id="status" class="w-full p-2 border rounded">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Update Status</button>
        </form>

        <div class="mt-6">
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back to Orders</a>
        </div>
    </div>
</div>
@endsection
