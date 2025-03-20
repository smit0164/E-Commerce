<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Container -->
    <div class="max-w-7xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <x-admin.stats-card title="Total Orders" value="1,250" icon="fa-shopping-cart" color="bg-indigo-100 text-indigo-600" />
            <x-admin.stats-card title="Total Revenue" value="$50,000" icon="fa-dollar-sign" color="bg-green-100 text-green-600" />
            <x-admin.stats-card title="Total Products" value="150" icon="fa-box" color="bg-blue-100 text-blue-600" />
            <x-admin.stats-card title="Total Customers" value="3,400" icon="fa-users" color="bg-purple-100 text-purple-600" />
            <x-admin.stats-card title="Pending Orders" value="45" icon="fa-spinner" color="bg-orange-100 text-orange-600" />
            <x-admin.stats-card title="Delivered Orders" value="1,200" icon="fa-check-circle" color="bg-teal-100 text-teal-600" />
            <x-admin.stats-card title="Out of Stock" value="8" icon="fa-exclamation-triangle" color="bg-red-100 text-red-600" />
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Latest Orders -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-box-open mr-2 text-gray-600"></i> Latest Orders
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Order ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-700">
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-4 py-4 font-medium">#1001</td>
                                    <td class="px-4 py-4">John Doe</td>
                                    <td class="px-4 py-4">$1,299</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-full">
                                            <i class="fas fa-check-circle mr-1.5"></i> Delivered
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-4 py-4 font-medium">#1002</td>
                                    <td class="px-4 py-4">Jane Smith</td>
                                    <td class="px-4 py-4">$499</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-orange-600 rounded-full">
                                            <i class="fas fa-spinner mr-1.5"></i> Pending
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Monthly Sales Chart -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-gray-600"></i> Monthly Sales
                    </h2>
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-chart-bar text-3xl mb-2"></i>
                            <p>Monthly Sales Chart Placeholder</p>
                            <p class="text-sm">Add Chart.js or another library to display sales trends</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Top-Selling Products -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-star mr-2 text-gray-600"></i> Top-Selling Products
                    </h2>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="/storage/products/iphone.jpg" alt="Product" class="w-10 h-10 object-cover rounded mr-3">
                                <span class="text-gray-700">iPhone 14 Pro</span>
                            </div>
                            <span class="text-gray-600 font-medium">150 sold</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="/storage/products/airpods.jpg" alt="Product" class="w-10 h-10 object-cover rounded mr-3">
                                <span class="text-gray-700">AirPods Pro</span>
                            </div>
                            <span class="text-gray-600 font-medium">120 sold</span>
                        </li>
                    </ul>
                </div>

                <!-- Top Customers -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-gray-600"></i> Top Customers
                    </h2>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <span class="text-gray-700">John Doe</span>
                            </div>
                            <span class="text-gray-600 font-medium">$5,200</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <span class="text-gray-700">Jane Smith</span>
                            </div>
                            <span class="text-gray-600 font-medium">$3,800</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection