<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.admin.login');
    }

    public function login(Request $request)
    {
        try {
            // Validate input
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back!');
            }
        }catch (\Exception $e) {
            return back()
                ->withErrors(['email' => 'Something went wrong. Please try again.'])
                ->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong while logging out.']);
        }
    }
}
