<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Tailwind</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
</head>
<body>
    <x-users.header />
    <x-users.navbar :categories="$categories" />
    <main>
        @yield('content')
    </main>
    <x-users.footer />
    <x-users.copy-right />
    <x-users.Toast />

    <script>
        $(document).ready(function () {
            // CSRF Setup
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            // Profile Dropdown
            const $profileButton = $('#profileButton');
            const $profileDropdown = $('#profileDropdown');
            if ($profileButton.length && $profileDropdown.length) {
                $profileButton.on('click', (e) => {
                    e.stopPropagation();
                    $profileDropdown.toggleClass('hidden');
                });
                $(document).on('click', (e) => {
                    if (!$profileDropdown.is(e.target) && !$profileDropdown.has(e.target).length) {
                        $profileDropdown.addClass('hidden');
                    }
                });
            }

            // Cart Logic with Data Attribute
            const isGuest = @json(auth()->guard('customer')->guest());
            $('[data-cart-action="add"]').on('click', function (e) {
                e.preventDefault(); // Prevent any default button behavior
                const $button = $(this);
                const productId = $button.data('product-id');
                console.log('Is Guest:', isGuest);

                if (isGuest) {
                    showToast('Please login to add items to your cart.', 'error');
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 500); // 2-second delay
                    return;
                }

                // Show loading state
                $button.find('span').text('Adding...');
                $button.find('.fa-bag-shopping').addClass('hidden');
                $button.find('.fa-spinner').removeClass('hidden');
                $button.prop('disabled', true);

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: 'POST',
                    data: { product_id: productId },
                    success: function (response) {
                        if (response.success) {
                            $('#cart-count').text(response.totalItems);
                            showToast(response.message, 'success');
                        }
                    },
                    error: function () {
                        showToast('Error adding product to cart.', 'error');
                    },
                    complete: function () {
                        // Reset button state
                        $button.find('span').text('Add to Cart');
                        $button.find('.fa-bag-shopping').removeClass('hidden');
                        $button.find('.fa-spinner').addClass('hidden');
                        $button.prop('disabled', false);
                    }
                });
            });

            // Session Messages
            @if (session('success'))
                showToast("{{ session('success') }}", 'success', 3000);
            @endif
            @if (session('error'))
                showToast("{{ session('error') }}", 'error', 3000);
            @endif
        });
    </script>
</body>
</html>