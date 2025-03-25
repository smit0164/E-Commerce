<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = Category::where('status', 'Active')
                ->withCount('products')
                ->get();
            
            
            $query = Product::where('status', 1);
            if ($request->has('categories')) {
                $categoryIds = (array) $request->input('categories');
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
    
            if ($request->filled('min')) {
                $query->where('price', '>=', $request->input('min'));
            }
            if ($request->filled('max')) {
                $query->where('price', '<=', $request->input('max'));
            }
    
            $products = $query->latest()->simplePaginate(6);
            $products->appends($request->only(['categories', 'min', 'max']));
    
            return view('pages.customer.products.index', compact('products', 'categories'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while loading products! Error: ' . $e->getMessage());
        }
    }

    public function show($slug)
    {
        try {
            $product = Product::where('slug', $slug)->firstOrFail();
            return view('pages.customer.products.show', compact('product'));
        } catch (Exception $e) {
            return redirect()->route('products.index')->with('error', 'Something went wrong while loading the product! Error: ' . $e->getMessage());
        }
    }
}
