<!-- resources/views/admin/orders.blade.php -->
@extends('layouts.admin.app')

@section('title', 'Order Management')

@section('content')
    <!-- Container -->
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center justify-between">
            Manage Orders
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i> Export CSV
            </button>
        </h2>

        <!-- Filter and Search -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="relative w-full sm:w-48">
                <select class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search orders..." class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pl-10 text-gray-700">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

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
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-5 font-medium">#1001</td>
                        <td class="px-6 py-5">John Doe</td>
                        <td class="px-6 py-5">$1,299</td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-full">
                                <i class="fas fa-check-circle mr-1.5"></i> Delivered
                            </span>
                        </td>
                        <td class="px-6 py-5">Mar 10, 2025</td>
                        <td class="px-6 py-5 flex space-x-4">
                            <button onclick="openOrderModal()" class="text-indigo-600 hover:text-indigo-800 transition" title="View Details">
                                <i class="fas fa-eye w-5 h-5"></i>
                            </button>
                            <button onclick="openUpdateModal()" class="text-yellow-600 hover:text-yellow-800 transition" title="Update Status">
                                <i class="fas fa-edit w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-5 font-medium">#1002</td>
                        <td class="px-6 py-5">Jane Smith</td>
                        <td class="px-6 py-5">$499</td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-orange-600 rounded-full">
                                <i class="fas fa-spinner mr-1.5"></i> Processing
                            </span>
                        </td>
                        <td class="px-6 py-5">Mar 11, 2025</td>
                        <td class="px-6 py-5 flex space-x-4">
                            <button onclick="openOrderModal()" class="text-indigo-600 hover:text-indigo-800 transition" title="View Details">
                                <i class="fas fa-eye w-5 h-5"></i>
                            </button>
                            <button onclick="openUpdateModal()" class="text-yellow-600 hover:text-yellow-800 transition" title="Update Status">
                                <i class="fas fa-edit w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-xl w-full max-w-lg shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Order Details</h3>
                <button onclick="closeOrderModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <div class="space-y-5 text-gray-700">
                <div class="flex justify-between"><span class="font-medium">Order ID:</span> <span>#1001</span></div>
                <div class="flex justify-between"><span class="font-medium">Customer:</span> <span>John Doe</span></div>
                <div class="flex justify-between"><span class="font-medium">Total:</span> <span>$1,299</span></div>
                <div class="flex justify-between"><span class="font-medium">Status:</span> <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-full"><i class="fas fa-check-circle mr-1.5"></i> Delivered</span></div>
                <div class="flex justify-between"><span class="font-medium">Date:</span> <span>Mar 10, 2025</span></div>
                <div class="flex justify-between flex-col">
                    <span class="font-medium mb-2">Items:</span>
                    <ul class="list-disc pl-5">
                        <li>iPhone 14 Pro - 1 unit</li>
                    </ul>
                </div>
                <div class="flex justify-between flex-col">
                    <span class="font-medium mb-2">Shipping Details:</span>
                    <span>123 Main St, City, Country</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="updateStatusModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-xl w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Update Order Status</h3>
                <button onclick="closeUpdateModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <form id="update-status-form" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-700">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</order-status-formption>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeUpdateModal()" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition duration-200">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Order Details Modal
        function openOrderModal() {
            const modal = document.getElementById('orderDetailsModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.classList.add('opacity-100');
            }, 10);
        }

        function closeOrderModal() {
            const modal = document.getElementById('orderDetailsModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // Update Status Modal
        function openUpdateModal() {
            const modal = document.getElementById('updateStatusModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.classList.add('opacity-100');
            }, 10);
        }

        function closeUpdateModal() {
            const modal = document.getElementById('updateStatusModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>
@endsection