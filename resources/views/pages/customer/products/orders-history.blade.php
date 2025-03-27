@extends('layouts.users.app')

@section('title', 'Order History')

@section('content')
<div class="bg-gray-100 min-h-screen antialiased">
    <div class="container mx-auto px-4 py-2">
        @if($userid->orders->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200 text-center">
                <p class="text-gray-600">No orders found yet. 
                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">Start shopping now!</a>
                </p>
            </div>
        @else
            <div class="w-full flex justify-center">
                <!-- Medium-sized Order List -->
                <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Your Orders</h2>
                        <h2 class="text-lg font-semibold text-gray-800 mx-auto">Order History</h2>
                        <div class="text-gray-600">
                            <span>Total Orders: </span> 
                            <span class="font-semibold text-gray-800">{{ $userid->orders->count() }}</span>
                        </div>
                    </div>
                    <div class="max-h-[70vh] overflow-y-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($userid->orders as $order)
                                    <tr class="hover:bg-indigo-50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">₹{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @switch($order->status)
                                                    @case('pending') bg-red-100 text-yellow-800 @break
                                                    @case('completed') bg-green-100 text-green-800 @break
                                                    @case('shipped') bg-blue-100 text-blue-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button 
                                                class="view-details text-red-600 hover:text-red-800 text-sm font-medium transition-colors"
                                                onclick= "showDetails({{ $order->id }})"
                                            >
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            

            <!-- Modal with Static Template -->
            <div id="order-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 relative">
                    <button onclick="hideDetails()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Order Details</h2>
                    <div id="details-content" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Address</h4>
                                <p id="shipping-address" class="mt-1 text-sm text-gray-600">Loading...</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Billing Address</h4>
                                <p id="billing-address" class="mt-1 text-sm text-gray-600">Loading...</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Payment Status</h4>
                                <p id="payment-status" class="mt-1 text-sm text-gray-600">Loading...</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Order Created</h4>
                                <p id="order-created" class="mt-1 text-sm text-gray-600">Loading...</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h4>
                            <div id="order-items" class="overflow-x-auto">
                                <table class="w-full text-sm text-gray-600 border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Product Name</th>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Quantity</th>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Price</th>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items-body" class="divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-center text-gray-600">Loading items...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button onclick="hideDetails()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        let currentOrderId = null;
        function hideDetails() {
                const modal = $('#order-modal');
                modal.addClass('hidden');
                currentOrderId = null;
                $('tr').removeClass('bg-indigo-100');
        }
        function showDetails(orderId) {
                const modal = $('#order-modal');
                const detailsContent = $('#details-content');

                // Highlight the selected row
                $('tr').each(function() {
                    $(this).removeClass('bg-indigo-100');
                    const button = $(this).find('.view-details');
                    if (button.length && button.data('order-id') == orderId) {
                        $(this).addClass('bg-indigo-100');
                    }
                });

                // Reset modal to loading state
                $('#shipping-address').html('Loading...');
                $('#billing-address').html('Loading...');
                $('#payment-status').html('Loading...');
                $('#order-created').html('Loading...');
                $('#order-items-body').html('<tr><td colspan="4" class="px-4 py-2 text-center text-gray-600">Loading items...</td></tr>');

                // Show the modal
                modal.removeClass('hidden');

                // jQuery AJAX request
                $.ajax({
                    url: "{{ route('customer.order.details', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId),
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(order) {
                        currentOrderId = orderId;
                        updateModal(order);
                    },
                    error: function(xhr, status, error) {
                        $('#details-content').html(`<p class="text-red-600">Error loading order details: ${xhr.status} - ${error}</p>`);
                    }
                });
            }
            function updateModal(order) {
                const shippingText = order.shipping_address ?
                    `${order.shipping_address.full_name || ''}<br>` +
                    `${order.shipping_address.address_line1 || 'N/A'}, ` +
                    `${order.shipping_address.address_line2 ? order.shipping_address.address_line2 + ', ' : ''}` +
                    `${order.shipping_address.city || ''} ${order.shipping_address.postal_code || ''}<br>` +
                    `${order.shipping_address.state || ''}, ${order.shipping_address.country || ''}` :
                    'Not provided';
                $('#shipping-address').html(shippingText);

                // Update Billing Address
                const billingText = order.billing_address ?
                    `${order.billing_address.full_name || ''}<br>` +
                    `${order.billing_address.address_line1 || 'N/A'}, ` +
                    `${order.billing_address.address_line2 ? order.billing_address.address_line2 + ', ' : ''}` +
                    `${order.billing_address.city || ''} ${order.billing_address.postal_code || ''}<br>` +
                    `${order.billing_address.state || ''}, ${order.billing_address.country || ''}` :
                    'Not provided';
                $('#billing-address').html(billingText);

                // Update Payment Status
                $('#payment-status').text(order.status.charAt(0).toUpperCase() + order.status.slice(1));

                // Update Order Created
                $('#order-created').text(new Date(order.created_at).toLocaleString());

                // Update Order Items
                const tbody = $('#order-items-body').empty();
                if (order.order_items.length === 0) {
                    tbody.html('<tr><td colspan="4" class="px-4 py-2 text-center text-gray-600">No items found for this order.</td></tr>');
                } else {
                    order.order_items.forEach(item => {
                        const row = $('<tr>');
                        row.append($('<td>').addClass('px-4 py-2').text(item.product_name));
                        row.append($('<td>').addClass('px-4 py-2').text(item.quantity));
                        row.append($('<td>').addClass('px-4 py-2').text(`₹${Number(item.unit_price).toFixed(2)}`));
                        row.append($('<td>').addClass('px-4 py-2').text(`₹${Number(item.subtotal).toFixed(2)}`));
                        tbody.append(row);
                    });
                }
            }

           
    </script>
</div>
@endsection