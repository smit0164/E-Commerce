<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|email|unique:customers,email|max:255',
                'phone' => 'required|digits:10',
                'password' => 'required|min:6|confirmed',
            ]);

           $customer= Customer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);
            Mail::to($customer->email)->queue(new RegisterUser($customer));
            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
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

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::guard('customer')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('home');
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->except('password'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again.')->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', 'Logged out successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to logout. Please try again.');
        }
    }
}