<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $products = Product::latest()->where('status', 1)->take(4)->get(); // Fetch latest 10 products
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
}
