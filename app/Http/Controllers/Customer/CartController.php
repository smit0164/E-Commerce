<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(Cart $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $productIds = array_column($cartItems, 'product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $cartItems = array_map(function ($cartItem) use ($products) {
            $product = $products[$cartItem['product_id']] ?? null;
            return [
                'id' => $cartItem['product_id'],
                'name' => $product->name ?? 'Unknown Product',
                'image' => $product->image ?? 'default.jpg',
                'availability' => $product && $product->stock > 0 ? 'In Stock' : 'Out of Stock',
                'quantity' => $cartItem['quantity'],
                'price' => $product->price ?? 0,
            ];
        }, $cartItems);
      
        return view('pages.products.cart', [
            'cartItems' => $cartItems,
            'totalPrice' => $this->cartService->totalPrice(),
        ]);
    }

    public function addCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $this->cartService->add($product);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'totalItems' => $this->cartService->totalItems(),
        ]);
    }

    public function updateCart(Request $request)
    {
        $this->cartService->update($request->product_id, $request->quantity);
        return response()->json([
            'message' => 'Cart updated successfully!',
            'total_price' => $this->cartService->totalPrice(),
            'totalItems' => $this->cartService->totalItems(),
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $this->cartService->remove($request->product_id);

        return response()->json([
            'message' => 'Item removed from cart!',
            'total_price' => $this->cartService->totalPrice(),
            'totalItems' => $this->cartService->totalItems(),
        ]);
    }
}
