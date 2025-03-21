<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function edit($slug)
    {
        // Eager load the 'categories' relationship
        $product = Product::with('categories')->where('slug', $slug)->firstOrFail();
        
        $categories = Category::all(); // Get all categories
        $selectedCategories = $product->categories->pluck('id')->toArray(); // Fetch selected category IDs
        
        return view('pages.admin.products.edit', compact('product', 'categories', 'selectedCategories'));
    }


    public function checkEditUnique(Request $request)
    {
        $productId = $request->input('product_id');
        $name = $request->input('name');
    
        // Check if another product (excluding the current one) has the same name
        $exists = Product::where('name', $name)
                         ->where('id', '!=', $productId)
                         ->exists();
    
        return response()->json(['isUnique' => !$exists]);
    }
    
    public function index()
    {
        $products = Product::with('categories')->latest()->get();
        return view('pages.admin.products.index', compact('products'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('pages.admin.products.create', compact('categories'));
    }

    public function checkUnique(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
        ]);
        $exists = Product::where('name', $request->name)->exists();
        return response()->json(['isUnique' => !$exists]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('products', $imageName, 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $request->status === 'active' ? 1 : 0,
                'quantity' => $request->quantity,
                'image' => $imageName,
            ]);

            $product->categories()->sync($request->category_id);
            if ($product->status === 1) {
                $product->status = "active";
            } else {
                $product->status = "Inactive";
            }
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * Show the form for editing the specified product.
     */


    /**
     * Update the specified product in storage.
     */
    public function update(Request $request)
    {
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $request->product_id,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'status' => 'nullable|boolean',
            'description' => 'nullable|string',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);
        $product = Product::findOrFail($request->product_id);
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->status = $request->status ?? 0; // Default to 0 if not provided
        $product->description = $request->description;
        $product->categories()->sync($request->category_id);
        if ($request->hasFile('image')) {
            // Delete old image if it exists in storage
            if ($product->image) {
                $imagePath = 'products/' . $product->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
    
            // Store new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $imageName, 'public');
    
            // Update product image
            $product->image = $imageName;
        }
        $product->save();
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request)
    {

        try {
            $productId = $request->input('product_id');
            $product = Product::findOrFail($productId);

            if ($product->image) {
                $imagePath = 'products/' . $product->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }
}
