<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class CheckoutService
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function processCheckout(array $data, array $cartItems)
    {
        $totalAmount = $this->cart->totalPrice();

        return DB::transaction(function () use ($data, $cartItems, $totalAmount) {
            $customerId = auth('customer')->id();
            if (!$customerId) {
                throw new \Exception('Customer not authenticated under "customer" guard');
            }

            // Determine address_id
            $addressId = $data['address_id'] ?? null;

            // If address_id is "new" or new address fields are provided, create a new address
            if ($addressId === 'new') {
                if (empty($data['address']) || empty($data['city']) || empty($data['state']) || empty($data['postal_code']) || empty($data['country'])) {
                    throw new \Exception('All new address fields are required.');
                }
                $addressId = Address::create([
                    'customer_id' => $customerId,
                    'customer_name' => $data['name'],
                    'customer_phone' => $data['phone'],
                    'customer_email' => $data['email'],
                    'address_line' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'postal_code' => $data['postal_code'],
                    'country' => $data['country'],
                    'is_default' => !auth('customer')->user()->addresses()->exists(),
                ])->id;
            } elseif (!$addressId || !\App\Models\Address::where('id', $addressId)->exists()) {
                // Fallback to default address if address_id is empty or invalid
                $defaultAddress = auth('customer')->user()->addresses()->where('is_default', true)->first();
                if (!$defaultAddress) {
                    throw new \Exception('No valid address selected and no default address found.');
                }
                $addressId = $defaultAddress->id;
            }

            // Create the order
            $order = Order::create([
                'customer_id' => $customerId,
                'address_id' => $addressId,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'cod',
                'status' => 'pending',
                'amount' => $totalAmount,
            ]);

            // Clear the cart
            $this->cart->clear();

            return $order;
        });
    }
}