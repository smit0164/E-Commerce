<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $products = Product::latest()->where('status', 1)->take(4)->get();
            $categories = Category::latest()->where('status', 'Active')->get(); // Fetch all categories
            return view('pages.customer.products.home', compact('products', 'categories'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function show($slug)
    {
        try {
            // Fetch the category by slug
            $category = Category::where('slug', $slug)->firstOrFail();

            // Query products separately with pagination
            $products = $category->products()
                ->where('status', 1)
                ->latest() // Optional: sort by latest
                ->simplePaginate(4); // Paginate 4 products per page

            return view('pages.customer.products.category-products', compact('category', 'products'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function showOrderHistory(Customer $userid)
    {
        // Eager load orders with shippingAddress, billingAddress, and orderItems with their products
        $userid->load('orders.shippingAddress', 'orders.billingAddress', 'orders.orderItems.product');
        
        return view('pages.customer.products.orders-history', compact('userid'));
    }
    public function getOrderDetails($orderId)
    {
        $order = Order::where('id', $orderId)
            ->with('shippingAddress', 'billingAddress', 'orderItems')
            ->firstOrFail();
    
        return response()->json($order);
    }
}
