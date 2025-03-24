<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function processCheckout(array $validatedData, array $cartItems)
    {
        return DB::transaction(function () use ($validatedData, $cartItems) {
            $customer = auth('customer')->user();
            $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
            $shippingAddressId = $validatedData['shipping_address_id'] ?? null;
            if ($shippingAddressId == 'new' || !$shippingAddressId) {
                // Check if a default shipping address already exists
                $hasDefaultShipping = Address::where('customer_id', $customer->id)
                    ->where('type', 'shipping')
                    ->where('is_default', true)
                    ->exists();
                
                $shippingAddressId = Address::create([
                    'customer_id' => $customer->id,
                    'type' => 'shipping',
                    'full_name' => $validatedData['name'],
                    'phone' => $validatedData['phone'],
                    'address_line1' => $validatedData['shipping']['address_line1'],
                    'address_line2' => null,
                    'city' => $validatedData['shipping']['city'],
                    'state' => $validatedData['shipping']['state'],
                    'postal_code' => $validatedData['shipping']['postal_code'],
                    'country' => $validatedData['shipping']['country'],
                    'is_default' => !$hasDefaultShipping, // True only if no default exists
                ])->id;
               
            }

            // Billing Address
            $billingAddressId = $validatedData['billing_address_id']??null;
            if ($billingAddressId == 'new' || !$billingAddressId) {
                // Check if a default billing address already exists
                $hasDefaultBilling = Address::where('customer_id', $customer->id)
                    ->where('type', 'billing')
                    ->where('is_default', true)
                    ->exists();

                $billingAddressId = Address::create([
                    'customer_id' => $customer->id,
                    'type' => 'billing',
                    'full_name' => $validatedData['name'],
                    'phone' => $validatedData['phone'],
                    'address_line1' => $validatedData['billing']['address_line1'],
                    'address_line2' => null,
                    'city' => $validatedData['billing']['city'],
                    'state' => $validatedData['billing']['state'],
                    'postal_code' => $validatedData['billing']['postal_code'],
                    'country' => $validatedData['billing']['country'],
                    'is_default' => !$hasDefaultBilling, // True only if no default exists
                ])->id;
            }

            // Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'total_amount' => $totalPrice,
                'status' => 'pending',
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $billingAddressId,
            ]);

            // Create Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'product_name' => $item['name'],
                ]);
            }

            // Create Payment
            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'payment_method' => $validatedData['payment_method'],
                'status' => 'pending',
            ]);

            // Clear the cart
            $this->cart->clear();

            return $order;
        });
    }
}