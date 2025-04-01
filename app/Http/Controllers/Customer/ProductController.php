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
        try 
        {
            // Fetch all categories from the database (Active status)
            $allCategories = Category::where('status', 'active')->withCount('products')->get();

            // Initialize the query for products
            $query = Product::where('status', 'active')->with('categories');
            $categories = []; // Initialize categories array for view

            // Filter by categories if provided in the request (for both AJAX and non-AJAX)
            if ($request->has('categories') && !empty($request->categories)) {
                $categories = explode(',', $request->input('categories'));
                $query->whereHas('categories', function ($q) use ($categories) {
                    $q->whereIn('categories.slug', $categories); // Filter by category slugs
                });
            }

            // Filter by price range if provided in the request (for both AJAX and non-AJAX)
            if ($request->has('price_min') && $request->price_min != '') {
                  $query->where('price', '>=', $request->input('price_min'));
            }
            if ($request->has('price_max') && $request->price_max != '') {
                $query->where('price', '<=', $request->input('price_max'));
            }

            // Pagination settings
            $perPage = 6;
            $products = $query->latest()->simplePaginate($perPage);

            // Handle AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pages.customer.products.partials.product_grid', compact('products'))->render(),
                    'pagination' => (string) $products->links('pagination::simple-tailwind'),
                ]);
            }

            // Return the main view with the filtered products, all categories, and selected categories
            return view('pages.customer.products.index', compact('products', 'allCategories', 'categories'));

        } catch (\Exception $e) {
            // Handle AJAX requests with an error message
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Something went wrong while fetching products. Please try again later.'
                ], 500);
            }

            // Redirect back with an error message for non-AJAX requests
            return redirect()->back()->with('error', 'Something went wrong while fetching products.');
        }
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
        try {
            $searchTerm = $request->input('search');

            // Fetch all categories for non-AJAX requests
            $allCategories = Category::where('status', 'active')->withCount('products')->get();

            // Build the query for active products with search term
            $query = Product::where('status', 'active')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
                $categories = []; // Initialize categories array for view

                // Filter by categories if provided in the request (for both AJAX and non-AJAX)
                if ($request->has('categories') && !empty($request->categories)) {
                    $categories = explode(',', $request->input('categories'));
                    $query->whereHas('categories', function ($q) use ($categories) {
                        $q->whereIn('categories.slug', $categories); // Filter by category slugs
                    });
                }
    
                // Filter by price range if provided in the request (for both AJAX and non-AJAX)
                if ($request->has('price_min') && $request->price_min != '') {
                      $query->where('price', '>=', $request->input('price_min'));
                }
                if ($request->has('price_max') && $request->price_max != '') {
                    $query->where('price', '<=', $request->input('price_max'));
                }

            // Handle AJAX request
            if ($request->ajax()) {
                $products = $query->latest()->take(5)->get();
                $total = $query->count();

                $html = view('pages.customer.products.partials.search_results', compact('products'))->render();

                return response()->json([
                    'html' => $html,
                    'hasMore' => $total > 5
                ]);
            }

            // Handle non-AJAX request
            $products = $query->latest()->simplePaginate(3)->appends(['search' => $searchTerm]);

            $allCategories = Category::where('status', 'active')->withCount('products')->get();
            return view('pages.customer.products.index', compact('products', 'allCategories', 'categories'));




        } catch (\Exception $e) {
            // Handle errors for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Something went wrong while searching products. Please try again later.'
                ], 500);
            }

            // Handle errors for non-AJAX requests
            return redirect()->back()->with('error', 'Something went wrong while searching products: ' . $e->getMessage());
        }
    }

       
    
   

}
