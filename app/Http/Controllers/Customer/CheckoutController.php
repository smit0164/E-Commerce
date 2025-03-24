<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cart;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Log;
use App\Models\Address;

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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'payment_method' => 'required|in:cod',
        ];
        $shippingId = $request->input('shipping_address_id');
        if (!isset($shippingId) || $shippingId === 'new') {
            // Apply rules for new shipping address
            $rules['shipping.address_line1'] = 'required|string|max:255';
            $rules['shipping.city'] = 'required|string|max:100';
            $rules['shipping.state'] = 'required|string|max:100';
            $rules['shipping.postal_code'] = 'required|digits_between:4,10';
            $rules['shipping.country'] = 'required|string|max:100';
        }
        $billingId = $request->input('billing_address_id');
        if (!isset($billingId) || $billingId === 'new') {
            // Apply rules for new billing address
            $rules['billing.address_line1'] = 'required|string|max:255';
            $rules['billing.city'] = 'required|string|max:100';
            $rules['billing.state'] = 'required|string|max:100';
            $rules['billing.postal_code'] = 'required|digits_between:4,10';
            $rules['billing.country'] = 'required|string|max:100';
        }
         
        // Validate the request with dynamic rules
        $validatedData = $request->validate($rules);
        
        $cartItems = $this->cart->getItems();
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty, friend!');
        }
    
        Log::info('Checkout Attempt Started', ['customer_id' => auth('customer')->id()]);
    
        try {
            $order = $this->checkoutService->processCheckout($validatedData, $cartItems);
            Log::info('Order placed successfully: ' . $order->id);
            return view('pages.products.order-success', compact('order'));
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong while placing your order: ' . $e->getMessage());
            Log::info('Session Data After Flash', session()->all());
            return redirect()->back()->withInput();
        }
    }
}
