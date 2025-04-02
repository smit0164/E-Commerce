<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateCustomerAddressRequest;
use App\Http\Requests\Customer\UpdateCustomerProfileRequest;


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
    public function showOrderHistory(Request $request)
    {
        // Get the authenticated user model
        $user = Auth::guard('customer')->user(); 
    
        // Eager load orders with shippingAddress, billingAddress, and orderItems with their products
        $user->load([
            'orders.shippingAddress',
            'orders.billingAddress',
            'orders.orderItems.product'
        ]);
    
        // Pass the user and related orders to the view
        return view('pages.customer.products.orders-history', compact('user'));
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
            $customer = Auth::guard('customer')->user(); // Get the full customer object
            
            // Optionally load related data (addresses and orders)
            $customer->load('addresses', 'orders');

            return view('pages.customer.customer', compact('customer'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to load your profile. Please try again later.');
        }
    }
    public function fetchCustomerAddress(Request $request)
    {
        try {
            // Get the address ID from the request
            $addressId = $request->input('addressId');
    
            // Fetch the address from the database
            $customerAddress = Address::find($addressId);
    
            // Check if the address exists
            if (!$customerAddress) {
                return response()->json(['error' => 'Address not found'], 404);
            }
    
            // Return the address data in JSON format
            return response()->json(['address' => $customerAddress], 200);
    
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json(['error' => 'Unable to fetch your address. Please try again later.'], 500);
        }
    }
    
    public function editCustomerAddress(UpdateCustomerAddressRequest $request)
    {
        // The request is already validated here
        
    
        try {
            // Find the address to update by its ID
            $address = Address::findOrFail($request->input('addressId'));
    
            // Update the address fields with current information excluding ID
            $address->address_line1 = $request->input('address_line1');
            $address->city = $request->input('city');
            $address->state = $request->input('state');
            $address->postal_code = $request->input('zip_code');
            if($request->has('is_default')){
                 $address->is_default=1;
            }else{
                $address->is_default=0;
            }
            
            // Save the changes to the database
            $address->save();
    
            // Redirect with success message
            return redirect()->route('customer.profile')->with('success', 'Address updated successfully!');
        } catch (\Exception $e) {
            // If any error occurs, redirect back with error message
            return redirect()->back()->with('error', 'Unable to update the address!');
        }
    }

    public function updateCustomerProfile(UpdateCustomerProfileRequest $request)
    {
        // Retrieve the currently authenticated customer
        $customer = Auth::guard('customer')->user();

        // Update the customer's profile
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Redirect to the profile page with a success message
        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

}
