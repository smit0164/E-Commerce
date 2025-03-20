<!-- resources/views/admin/customers.blade.php -->
@extends('layouts.admin.app')

@section('title', 'Customer Management')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Manage Customers</h2>

        <!-- Customer Table -->
        <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    <!-- Sample Row -->
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-5 font-medium">1</td>
                        <td class="px-6 py-5">John Doe</td>
                        <td class="px-6 py-5">john.doe@example.com</td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-5 flex space-x-4">
                            <button onclick="openOrderHistoryModal()" class="text-indigo-600 hover:text-indigo-800 transition" title="View Order History">
                                <i class="fas fa-history w-5 h-5"></i>
                            </button>
                            <button onclick="openBlockModal()" class="text-red-600 hover:text-red-800 transition" title="Block Customer">
                                <i class="fas fa-ban w-5 h-5"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order History Modal -->
    <div id="orderHistoryModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-xl w-full max-w-3xl shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Order History - John Doe</h3>
                <button onclick="closeOrderHistoryModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">1001</td>
                            <td class="px-6 py-4">2023-05-15</td>
                            <td class="px-6 py-4">$999.00</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Delivered
                                </span>
                            </td>
                        </tr>
                        <!-- More sample orders can be added here -->
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeOrderHistoryModal()" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Block/Activate Confirmation Modal -->
    <div id="blockModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-xl w-full max-w-md shadow-2xl transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Confirm Action</h3>
                <button onclick="closeBlockModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-times w-6 h-6"></i>
                </button>
            </div>
            <p class="text-gray-700 mb-6">Are you sure you want to block <span id="block-customer-name" class="font-medium">John Doe</span>? This will prevent them from making purchases.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBlockModal()" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition duration-200">
                    Cancel
                </button>
                <button class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition duration-200">
                    Block
                </button>
            </div>
        </div>
    </div>

    <script>
        // Order History Modal
        function openOrderHistoryModal() {
            const modal = document.getElementById('orderHistoryModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.classList.add('opacity-100');
            }, 10);
        }

        function closeOrderHistoryModal() {
            const modal = document.getElementById('orderHistoryModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // Block/Activate Modal
        function openBlockModal() {
            const modal = document.getElementById('blockModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.classList.add('opacity-100');
            }, 10);
        }

        function closeBlockModal() {
            const modal = document.getElementById('blockModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>
@endsection