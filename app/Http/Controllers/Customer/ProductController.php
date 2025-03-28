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
        $categories = Category::where('status', 'Active')->withCount('products')->get();
        

        $query = Product::where('status', 1);
        if ($request->has('categories')) {
            $categoryIds = array_filter(explode(',', $request->input('categories')));
            if (!empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        if ($priceMin) $query->where('price', '>=', floatval($priceMin));
        if ($priceMax) $query->where('price', '<=', floatval($priceMax));

        $products = $query->latest()->simplePaginate(6);
        $products->appends($request->only(['categories', 'price_min', 'price_max']));

        return view('pages.customer.products.index', compact('products', 'categories'));
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

    public function filter(Request $request)
    {
        $perPage = 6;

        // Build the query
        $query = Product::where('status', 1)
            ->with('categories'); // Eager load categories

        // Filter by categories
        if ($request->has('categories')) {
            $categoryIds = array_filter((array) $request->input('categories')); // Remove empty values
            if (!empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        // Filter by price range
        $priceMin = $request->input('price_min', null);
        $priceMax = $request->input('price_max', null);
        if ($priceMin !== null && $priceMax !== null) {
            $priceMin = floatval($priceMin);
            $priceMax = floatval($priceMax);
            if ($priceMin <= $priceMax) {
                $query->whereBetween('price', [$priceMin, $priceMax]);
            }
        } elseif ($priceMin !== null) {
            $query->where('price', '>=', floatval($priceMin));
        } elseif ($priceMax !== null) {
            $query->where('price', '<=', floatval($priceMax));
        }

        // Paginate filtered products
        $products = $query->latest()->simplePaginate($perPage);
        $products->appends($request->only(['categories', 'price_min', 'price_max']));

        // Render the product grid using a Blade partial
        return response()->json([
            'html' => view('pages.customer.products.partials.product_grid', compact('products'))->render(),
            'pagination' => (string) $products->links('pagination::simple-tailwind'),
        ]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Product::where('status', 1)
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });

        $products = $query->latest()->take(5)->get(); // Limit to 5 for modal
        $total = $query->count(); // Total matching products

        $html = view('pages.customer.products.partials.search_results', compact('products'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $total > 5 // Show "View More" if more than 5 results
        ]);
    }
}
