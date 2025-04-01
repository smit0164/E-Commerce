<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $products = Product::latest()->where('status','active')->take(4)->get();
            $categories = Category::latest()->where('status', 'active')->get(); // Fetch all categories
            return view('pages.customer.products.home', compact('products', 'categories'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function show($slug)
    {
        try {
            $category = Category::where('slug', $slug)
            ->with(['products' => function($query) {
                $query->where('status','active')
                      ->orderBy('id', 'desc'); // Optional: sort by latest
            }])
            ->firstOrFail();
        
        // Get the products with pagination
          $products = $category->products()->simplePaginate(4);  // Paginate 4 products per page
          return view('pages.customer.products.category-products', compact('category', 'products'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function showOrderHistory(Customer $userid)
    {
        // Eager load orders with shippingAddress, billingAddress, and orderItems with their products
        $userid->load([
            'orders.shippingAddress',
            'orders.billingAddress',
            'orders.orderItems.product'
        ]);
        
        
        return view('pages.customer.products.orders-history', compact('userid'));
    }
    public function getOrderDetails($orderId)
    {
        try {
            $order = Order::where('id', $orderId)
                ->with('shippingAddress', 'billingAddress', 'orderItems')
                ->firstOrFail();
            return response()->json($order);
        }catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function customerProfile(Request $request)
    {
        try {
            $userId = Auth::guard('customer')->user()->id; // Get the logged-in user's ID
            $customer = Auth::guard('customer')->user(); // Get the full customer object
            
            // Optionally load related data (addresses and orders)
            $customer->load('addresses', 'orders');

            return view('pages.customer.customer', compact('customer'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to load your profile. Please try again later.');
        }
    }
    
}
