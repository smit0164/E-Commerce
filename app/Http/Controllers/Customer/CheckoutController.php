<?php

namespace App\Http\Controllers\Customer;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cart;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Log;
use App\Models\Address;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
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
        try {
            $cartItems = $this->cart->getItems();
            $totalPrice = $this->cart->totalPrice();
            return view('pages.customer.checkout.index', compact('cartItems', 'totalPrice'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while loading the checkout page.');
        }
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
    
        try {
            $order = $this->checkoutService->processCheckout($validatedData, $cartItems);
            Log::info('Order placed successfully: ' . $order->id);
            Mail::to($order->customer->email)->queue(new OrderPlaced($order));
            return view('pages.customer.products.order-success', compact('order'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput();
        }
    }

    public function showOrdersDetails(Order $id){
        $order = $id;
        return view('pages.customer.products.order-details', compact('order'));
    } 
}
