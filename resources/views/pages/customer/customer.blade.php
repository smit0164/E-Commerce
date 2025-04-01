@extends('layouts.users.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-[280px,1fr] gap-8">
        <!-- Sidebar -->
        <aside class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Account Menu</h3>
            <div class="space-y-3">
                <a href="" class="block text-gray-600 hover:text-primary transition-colors font-medium py-2 px-3 rounded-md bg-gray-100">Profile</a>
                <a href="" class="block text-gray-600 hover:text-primary transition-colors font-medium py-2 px-3 rounded-md hover:bg-gray-100">My Orders</a>
                <a href="" class="block text-gray-600 hover:text-primary transition-colors font-medium py-2 px-3 rounded-md hover:bg-gray-100">Addresses</a>
                <a href="{{ route('logout') }}" class="block text-gray-600 hover:text-red-600 transition-colors font-medium py-2 px-3 rounded-md hover:bg-gray-100">Logout</a>
            </div>
        </aside>

        <!-- Profile Section -->
        <section class="pb-8">
            <!-- Success/Error Messages -->
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Personal Information -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Personal Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Name:</span>
                        <span class="text-gray-800">{{ $customer->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Email:</span>
                        <span class="text-gray-800">{{ $customer->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Phone:</span>
                        <span class="text-gray-800">{{ $customer->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Status:</span>
                        <span class="text-gray-800">{{ $customer->status ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
                <a href="" class="mt-4 inline-block bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">Edit Profile</a>
            </div>

            <!-- Addresses -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Your Addresses</h3>
                @if ($customer->addresses->isEmpty())
                    <p class="text-gray-600">No addresses found.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($customer->addresses as $address)
                            <div class="border border-gray-200 p-4 rounded-lg">
                                <p class="text-gray-800">{{ $address->street }}, {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                                <a href="" class="text-primary hover:underline text-sm">Edit</a>
                            </div>
                        @endforeach
                    </div>
                @endif
                <a href="" class="mt-4 inline-block bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">Add Address</a>
            </div>

            <!-- Orders -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 uppercase tracking-wide">Your Orders</h3>
                @if ($customer->orders->isEmpty())
                    <p class="text-gray-600">No orders found.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-gray-600 font-semibold">Order ID</th>
                                    <th class="px-4 py-2 text-gray-600 font-semibold">Total</th>
                                    <th class="px-4 py-2 text-gray-600 font-semibold">Status</th>
                                    <th class="px-4 py-2 text-gray-600 font-semibold">Date</th>
                                    <th class="px-4 py-2 text-gray-600 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer->orders as $order)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 text-gray-800">{{ $order->id }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $order->total ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $order->status ?? 'Pending' }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2">
                                            <a href="" class="text-primary hover:underline text-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection