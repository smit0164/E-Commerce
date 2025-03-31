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
        try {
            $cartItems = $this->cartService->getItems();
            if (empty($cartItems)) {
                return view('pages.customer.cart.index', [
                    'cartItems' => [],
                    'totalPrice' => 0,
                ]);
            }

            $productIds = array_column($cartItems, 'product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
           
            $cartItems = array_map(function ($cartItem) use ($products) {
                $product = $products[$cartItem['product_id']] ?? null;
                return [
                    'id' => $cartItem['product_id'],
                    'name' => $product->name ?? 'Unknown Product',
                    'image' => $product->image ?? 'default.jpg',
                    'availability' => $product && $product->quantity > 0 ? 'In Stock' : 'Out of Stock',
                    'quantity' => $cartItem['quantity'],
                    'price' => $product?->price ?? 0,
                ];
            }, $cartItems);

            return view('pages.customer.cart.index', [
                'cartItems' => $cartItems,
                'totalPrice' => $this->cartService->totalPrice(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to load cart. Please try again.');
        }
    }

    public function addCart(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $this->cartService->add($product);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'totalItems' => $this->cartService->totalItems(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart. Please try again.',
            ], 500);
        }
    }

    public function updateCart(Request $request)
    {
        try {
            $this->cartService->update($request->product_id, $request->quantity);
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'total_price' => $this->cartService->totalPrice(),
                'totalItems' => $this->cartService->totalItems(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart. Please try again.',
            ], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $this->cartService->remove($request->product_id);
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'total_price' => $this->cartService->totalPrice(),
                'totalItems' => $this->cartService->totalItems(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart. Please try again.',
            ], 500);
        }
    }
}
