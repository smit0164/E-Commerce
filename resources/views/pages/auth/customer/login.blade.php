@extends('layouts.auth.admin-auth')
@section('content')

        <h2 class="text-xl font-semibold text-gray-700 text-center">Login</h2>
        <p class="text-gray-500 text-sm text-center mb-4">Sign in to your account</p>

        <form action="{{ route('login') }}" method="post" autocomplete="on" id="customerLoginForm">
            @csrf
            <div class="space-y-3">
                <div>
                    <label for="email" class="text-gray-600 block mb-1">Email Address</label>
                    <input type="email" name="email" id="email"
                        class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="youremail@domain.com"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password" class="text-gray-600 block mb-1">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full border px-3 py-2.5 rounded-md text-gray-700 text-sm placeholder-gray-400 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="*******">
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit"
                    class="w-full py-2.5 text-center text-white bg-red-500 border border-red-500 rounded-md hover:bg-red-600 transition font-medium shadow-sm">
                    Login
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-gray-600 text-sm">Don't have an account?
            <a href="{{ route('register') }}" class="text-red-500 font-medium hover:underline">Register now</a>
        </p>
    </div>

    <script>
        $(document).ready(function() {
            $("#customerLoginForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please enter your password",
                    }
                },
                errorElement: "span",
                errorClass: "text-red-500 text-sm mt-1 block",
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
                    form.submit();
                }
            });
        });
    </script>

@endsection