@extends('layouts.users.app')

@section('content')

    <div class="w-full max-w-4xl mx-auto py-10">
        <div class="flex flex-col lg:flex-row items-start gap-6">
            <!-- Cart Items -->
            <div class="lg:w-2/3 flex-1">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center lg:text-left">Your Cart</h2>

                @if (empty($cartItems))
                    <p class="text-gray-600 text-center">Your cart is empty.</p>
                @else
                    <div id="cart-items-container" class="space-y-4">
                        @foreach ($cartItems as $cart)
                            <x-users.cart-item :id="$cart['id']" :image="$cart['image']" :name="$cart['name']" :availability="$cart['availability']"
                                :quantity="$cart['quantity']" :price="$cart['price']" />
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cart Summary -->
            <div class="lg:w-1/3 flex-shrink-0">
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Cart Summary</h3>
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm font-medium text-gray-700">Total:</p>
                        <p id="cart-total-price" class="text-xl font-bold text-red-500">
                            ₹{{ number_format($totalPrice, 2) }}
                        </p>
                    </div>

                    <!-- Proceed to Checkout Button -->
                    <a href="{{ route('checkout') }}"
                        class="block w-full bg-red-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i> Proceed to Checkout
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- AJAX for Cart Update -->
    <script>
        $(document).ready(function() {
            function updateCartSummary(response) {
                if (response.total_price !== undefined) {
                    let formattedPrice = `₹${parseFloat(response.total_price).toFixed(2)}`;
                    $('#cart-total-price').text(formattedPrice);
                } else {
                    console.error("Error: total_price not found in response", response);
                }

                showToast(response.message, 'success', 2000);
            }

            // Update cart quantity
            $('.update-quantity').on('click', function() {
                let productId = $(this).data('product-id');
                let action = $(this).data('action');
                let inputField = $(`.cart-quantity[data-product-id="${productId}"]`);
                let quantity = parseInt(inputField.val());

                if (action === 'increase') {
                    quantity++;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                }

                inputField.val(quantity);

                $.ajax({
                    url: "{{ route('cart.update') }}",
                    method: "POST",
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#cart-count').text(response.totalItems);
                        updateCartSummary(response);
                    }
                });
            });

            // Remove from cart
            $('.remove-from-cart').on('click', function() {
                let productId = $(this).data('product-id');
                let itemRow = $(this).closest('.cart-item');

                $.ajax({
                    url: "{{ route('cart.remove') }}",
                    method: "POST",
                    data: {
                        product_id: productId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        itemRow.remove();
                        $('#cart-count').text(response.totalItems);
                        if ($('.cart-item').length === 0) {
                            $('#cart-items-container').html(
                                '<p class="text-gray-600 text-center">Your cart is empty.</p>'
                            );
                        }

                        updateCartSummary(response);
                    }
                });
            });
        });
    </script>

@endsection
