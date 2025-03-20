<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('pages.auth.customer.register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|digits:10',
            'password' => 'required|min:6|confirmed',
        ]);
        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function showLoginForm()
    {
        return view('pages.auth.customer.login');
    }

    public function login(Request $request)
    {

        try {
            // Validate input
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
        }catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->except('password'));
        }catch (\Exception $e){
            return back()
                ->withErrors(['email' => 'Something went wrong. Please try again.'])
                ->withInput($request->except('password'));
        }
    }
    public function logout(Request $request)
    {  
        try{
             
            Auth::guard("customer")->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect(route("home"));
        }catch(\Exception $e){
            return back()->withErrors(['error' => 'Something went wrong while logging out.']);
        }
       
    }
}
