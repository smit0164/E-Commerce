<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cart;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Log;
use App\Models\Address;
use App\Http\Requests\Customer\CheckoutRequest;
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
        return view('pages.customer.checkout.index', compact('cartItems', 'totalPrice'));
    }
    public function store(CheckoutRequest $request)
    {
        // Validate the request with dynamic rules
        $validatedData = $request->validated();
    
        // Manually add shipping_address_id and billing_address_id to validated data if numeric
        $shippingId = $request->input('shipping_address_id');
        if (is_numeric($shippingId)) {
            $validatedData['shipping_address_id'] = $shippingId;
        }
        $billingId = $request->input('billing_address_id');
        if (is_numeric($billingId)) {
            $validatedData['billing_address_id'] = $billingId;
        }
    
        $cartItems = $this->cart->getItems();
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty, friend!');
        }
    
        Log::info('Checkout Attempt Started', ['customer_id' => auth('customer')->id()]);
        Log::info('Validated Data', $validatedData);
    
        try {
            $order = $this->checkoutService->processCheckout($validatedData, $cartItems);
            Log::info('Order placed successfully: ' . $order->id);
            return view('pages.customer.products.order-success', compact('order'));
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong while placing your order: ' . $e->getMessage());
            Log::info('Session Data After Flash', session()->all());
            return redirect()->back()->withInput();
        }
    }
}
