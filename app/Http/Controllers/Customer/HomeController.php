<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(10)->get(); // Fetch latest 10 products
        $categories = Category::all(); // Fetch all categories

        return view('pages.products.home', compact('products', 'categories'));
    }
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->with('products')->firstOrFail();
        return view('pages.products.category-products', compact('category'));
    }
}
