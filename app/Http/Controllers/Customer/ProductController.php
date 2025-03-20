<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            if ($request->sort == 'price_low_high') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_high_low') {
                $query->orderBy('price', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        }

        $products = $query->paginate(12);

        return view('pages.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('pages.products.show', compact('product'));
    }
}
