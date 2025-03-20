<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Tailwind</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    <!-- Tailwind & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const profileButton = $('#profileButton');
            const profileDropdown = $('#profileDropdown');

            if (profileButton.length && profileDropdown.length) {
                profileButton.on('click', function (event) {
                    event.stopPropagation();
                    profileDropdown.toggleClass('hidden');
                });

                $(document).on('click', function (event) {
                    if (!profileDropdown.is(event.target) && !profileDropdown.has(event.target).length) {
                        profileDropdown.addClass('hidden');
                    }
                });
            }

            var isGuest = @json(auth()->guard('customer')->guest());

            $('.add-to-cart').click(function () {
                var productId = $(this).data('product-id');
                var button = $(this);

                if (isGuest) {
                    showToast('Please login to add items to your cart.', 'error');
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                    return;
                }

                button.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Adding...');

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: 'POST',
                    data: {
                        product_id: productId
                    },
                    success: function (response) {
                        if (response.success) {
                            console.log(response);
                            $('#cart-count').text(response.totalItems);
                            button.prop('disabled', false).html('<i class="fa-solid fa-bag-shopping"></i> Add to Cart');
                            showToast(response.message, "success");
                        }
                    },
                    error: function () {
                        showToast('Error adding product to cart.', 'error');
                        button.prop('disabled', false).html('<i class="fa-solid fa-bag-shopping"></i> Add to Cart');
                    }
                });
            });
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
