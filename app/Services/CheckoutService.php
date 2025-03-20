<?php
namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class CheckoutService
{
    public function processCheckout($request, $cartItems)
    {
        $totalAmount = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cartItems));

        $address = Address::create([
            'name' => 'Default',
            'customer_name' => $request->name,
            'customer_phone' => $request->phone,
            'address_line' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);

        $order = Order::create([
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'address_id' => $address->id,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cod',
            'status' => 'pending',
            'amount' => $totalAmount,
        ]);

        session()->forget('cart');

        return $order;
    }
}