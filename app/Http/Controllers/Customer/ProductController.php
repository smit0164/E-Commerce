<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all categories from the database (Active status)
        $allCategories = Category::where('status', 'Active')->withCount('products')->get();
    
        // Initialize the query for products
        $query = Product::where('status', 'active')->with('categories');
        $categories=[];
        // Filter by categories if provided in the request
        if ($request->has('categories') && !empty($request->categories)) {
            // Get the selected categories from the URL query string
            $categories = explode(',', $request->input('categories'));
            
            // Apply the filter for categories using `whereHas`
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('categories.slug', $categories); // Filter by category slugs
            });
        }
    
        // Filter by price range if provided in the request
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->input('price_max'));
        }
    
        // Paginate the products (6 products per page)
        $perPage = 6;
        $products = $query->latest()->simplePaginate($perPage);
    
        // If the request is an AJAX request, return the updated product grid and pagination links
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.customer.products.partials.product_grid', compact('products'))->render(),
                'pagination' => (string) $products->links('pagination::simple-tailwind'),
            ]);
        }
        
    
        // Return the main view with the filtered products, all categories, and selected categories
        return view('pages.customer.products.index', compact('products', 'allCategories', 'categories'));
    }
    

    public function show($slug)
    {
        try {
            $product = Product::where('slug', $slug)->firstOrFail();
            return view('pages.customer.products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Something went wrong while loading the product! Error: ' . $e->getMessage());
        }
    }

   
    

    public function search(Request $request)
    {
            $searchTerm = $request->input('search');
           
            $query = Product::where('status', 1)
                ->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });

            if ($request->ajax()) {
                $products = $query->latest()->take(5)->get();
                $total = $query->count();

                $html = view('pages.customer.products.partials.search_results', compact('products'))->render();

                return response()->json([
                    'html' => $html,
                    'hasMore' => $total > 5
                ]);
            }

            $products = $query->latest()->simplePaginate(8);
            return view('pages.customer.products.search_results_button', compact('products', 'searchTerm'));
    }

}
