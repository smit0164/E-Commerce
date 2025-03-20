<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - E-Commerce</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-md border-t-4 border-red-500 px-6 py-6 rounded-lg">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Company Logo" class="w-24 ">
        </div>

        <!-- Heading -->
        <h2 class="text-xl font-semibold text-gray-700 text-center">Admin Login</h2>
        <p class="text-gray-500 text-sm text-center mb-6">Sign in to your account</p>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <form id="adminLoginForm" action="{{ route('admin.login') }}" method="POST" novalidate>
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="text-gray-600 block mb-1 font-medium">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full border border-gray-300 px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400"
                        placeholder="youremail@domain.com"
                        required
                    >
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="text-gray-600 block mb-1 font-medium">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full border border-gray-300 px-3 py-2.5 rounded-md text-gray-700 text-sm  placeholder-gray-400"
                        placeholder="•••••••"
                        required
                    >
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button 
                    type="submit"
                    class="w-full py-2.5 text-center text-white bg-red-500 border border-red-500 rounded-md hover:bg-red-600 transition font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-red-200 disabled:opacity-50"
                >
                    Login
                </button>
            </div>
        </form>
    </div>

   
    <script>
        $(document).ready(function () {
            $("#adminLoginForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 6 characters long"
                    }
                },
                errorClass: "text-red-500 text-sm mt-1",
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass("border-red-500").removeClass("border-gray-300");
                },
                unhighlight: function(element) {
                    $(element).removeClass("border-red-500").addClass("border-gray-300");
                },
                submitHandler: function(form) {
                    if ($(form).valid()) {
                        $(form).find('button[type="submit"]').prop('disabled', true);
                        form.submit();
                    }
                }
            });
        });
    </script>

</body>
</html>
