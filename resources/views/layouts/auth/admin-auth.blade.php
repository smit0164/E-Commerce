<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  
    <div class="w-full max-w-md bg-white shadow-md border-t-4 border-red-500 px-6 py-6 rounded-lg">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <x-users.logo/>
        </div>

        @yield('content')

        <!-- Toast Component -->
        <x-users.Toast />

        <!-- Show Toast Notifications -->
        <script>
            @if (session('error'))
                showToast("{{ session('error') }}", 'error', 2000);
            @endif

            @if (session('success'))
                showToast("{{ session('success') }}", 'success', 2000);
            @endif
        </script>
    </div>

</body>
</html>
