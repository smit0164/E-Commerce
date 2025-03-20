<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class Cart
{
    protected $sessionKey = 'cart';

    public function getItems()
    {
        return Session::get($this->sessionKey, []);
    }
    
    public function add($product)
    {
        $cart = $this->getItems();
        $productId = $product->id;
    
        if (isset($cart[$productId])) {
            // Increase quantity while keeping other details
            $cart[$productId]['quantity'] += 1;
        } else {
            // Store full product details
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1, // Initial cart quantity
                'image' => $product->image,
            ];
        }
        Session::put($this->sessionKey, $cart);
        return $cart;
    }
    
    

    public function update($productId, $quantity)
    {
        $cart = $this->getItems();
        
        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                $cart[$productId]['quantity'] = $quantity;
            } else {
                unset($cart[$productId]);
            }
        }

        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    public function remove($productId)
    {
        $cart = $this->getItems();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put($this->sessionKey, $cart);
        }

        return $cart;
    }

    public function clear()
    {
        Session::forget($this->sessionKey);
    }

    public function totalItems()
    {
        return array_sum(array_column($this->getItems(), 'quantity'));
    }

    public function totalPrice()
    {
        $cart = $this->getItems(); // Directly get cart items
        $total = 0;
    
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    
        return $total;
    }
    
    
}
