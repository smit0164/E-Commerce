<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cart;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cart;
    protected $checkoutService;

    public function __construct(Cart $cart, CheckoutService $checkoutService)
    {
        
        $this->cart = $cart;
        $this->checkoutService = $checkoutService;
    }

    public function index()
    {
        $cartItems = $this->cart->getItems();
        $totalPrice = $this->cart->totalPrice();
        return view('pages.products.checkout', compact('cartItems', 'totalPrice'));
    }

    public function store(Request $request)
    {
        if (!auth('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please log in to proceed.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'address_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'new' && !\App\Models\Address::where('id', $value)->exists()) {
                        $fail('The selected address id is invalid.');
                    }
                },
            ],
            'address' => 'required_if:address_id,new|string|max:255|nullable',
            'city' => 'required_if:address_id,new|string|max:100|nullable',
            'state' => 'required_if:address_id,new|string|max:100|nullable',
            'postal_code' => 'required_if:address_id,new|string|regex:/^[0-9]{4,10}/|nullable', // Fixed: Added closing /
            'country' => 'required_if:address_id,new|string|max:100|nullable',
        ]);

        $cartItems = $this->cart->getItems();
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty, friend!');
        }

        try {
            $order = $this->checkoutService->processCheckout($validatedData, $cartItems);
            Log::info('Order placed successfully: ' . $order->id);
            return redirect()->route('home')->with('success', "Thanks for your order! Your order ID is #{$order->id}");
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while placing your order: ' . $e->getMessage());
        }
    }
}