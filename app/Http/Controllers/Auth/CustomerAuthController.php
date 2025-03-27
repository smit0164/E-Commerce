<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Customer\CustomerRegisterRequest;
use App\Http\Requests\Auth\Customer\CustomerLoginRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\RegisterUser;
use Illuminate\Support\Facades\Mail;

class CustomerAuthController extends Controller
{
    public function showRegisterForm()
    {
        try {
            return view('pages.auth.customer.register');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Unable to load registration form. Please try again.');
        }
    }

    public function register(CustomerRegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $customer = Customer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);
            Mail::to($customer->email)->queue(new RegisterUser($customer));
            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to register. Please try again.')->withInput();
        }
    }

    public function showLoginForm()
    {
        try {
            return view('pages.auth.customer.login');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Unable to load login form. Please try again.');
        }
    }

    public function login(CustomerLoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (Auth::guard('customer')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('home');
            }

            return back()->with('error', 'The provided credentials do not match our records.')
            ->withInput($request->except('password'));
        }catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again.')->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('customer')->logout();
            $request->session()->forget('customer_auth');
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', 'Logged out successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to logout. Please try again.');
        }
    }
}