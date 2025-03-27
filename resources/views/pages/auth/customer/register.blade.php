@extends('layouts.auth.admin-auth')
@section('content')

    <!-- Heading -->
    <h2 class="text-xl uppercase font-semibold text-gray-700 text-center">Create an Account</h2>
    <p class="text-gray-500 text-sm text-center mb-6">Register as a new customer</p>

    <!-- Form -->
    <form id="customerRegisterForm" action="{{ route('register') }}" method="POST" autocomplete="on" novalidate>
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="text-gray-600 block mb-1 font-medium">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    value="{{ old('name') }}"
                    class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="Full Name"
                    required
                    aria-describedby="name-error"
                >
                @error('name')
                    <span id="name-error" class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="email" class="text-gray-600 block mb-1 font-medium">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}"
                    class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="youremail@domain.com"
                    required
                    aria-describedby="email-error"
                >
                @error('email')
                    <span id="email-error" class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="phone" class="text-gray-600 block mb-1 font-medium">Phone Number</label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone"
                    value="{{ old('phone') }}"
                    class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="9876543210"
                    required
                    aria-describedby="phone-error"
                >
                @error('phone')
                    <span id="phone-error" class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="text-gray-600 block mb-1 font-medium">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="•••••••"
                    required
                    aria-describedby="password-error"
                >
                @error('password')
                    <span id="password-error" class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-gray-600 block mb-1 font-medium">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="•••••••"
                    required
                    aria-describedby="password_confirmation-error"
                >
                @error('password_confirmation')
                    <span id="password_confirmation-error" class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <button 
                type="submit"
                class="w-full py-2.5 text-center text-white bg-red-500 border border-red-500 rounded-md hover:bg-red-600 transition font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-red-200 disabled:opacity-50"
            >
                Create Account
            </button>
        </div>
    </form>

    <p class="mt-4 text-center text-gray-600 text-sm">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-red-500 font-medium hover:underline">Login now</a>
    </p>

    <script>
        $(document).ready(function () {
            $("#customerRegisterForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your full name",
                        minlength: "Name must be at least 3 characters long"
                    },
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    phone: {
                        required: "Please enter your phone number",
                        digits: "Please enter only digits",
                        minlength: "Phone number must be 10 digits",
                        maxlength: "Phone number must be 10 digits"
                    },
                    password: {
                        between: "Please enter a password",
                        minlength: "Password must be at least 6 characters long"
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    }
                },
                errorElement: "span",
                errorClass: "text-red-500 text-sm mt-1 block",
                highlight: function(element) {
                    $(element).addClass("border-red-500").removeClass("border-gray-300");
                },
                unhighlight: function(element) {
                    $(element).removeClass("border-red-500").addClass("border-gray-300");
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]').prop('disabled', true).text('Registering...');
                    form.submit();
                }
            });

        });
    </script>
@endsection