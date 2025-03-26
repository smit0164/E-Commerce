<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
class ProductController extends Controller
{
    public function index()
    {
        try {
            // Fetch only non-deleted products with categories
            $products = Product::with('categories')->latest()->simplePaginate(5);
            return view('pages.admin.products.index', compact('products'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading products: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            return view('pages.admin.products.create', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading create page: ' . $e->getMessage());
        }
    }
    
    public function store(ProductRequest $request)
    {
        
        try {
           
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('products', $imageName, 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'status' => $request->status ?? 0,
                'description' => $request->description,
                'image' => $imageName ?? null,
            ]);

            $product->categories()->sync($request->category_id);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }
    public function edit($slug)
    {
        try {
            $product = Product::with('categories')->where('slug', $slug)->firstOrFail();
            $categories = Category::all();
            $selectedCategories = $product->categories->pluck('id')->toArray();
            return view('pages.admin.products.edit', compact('product', 'categories', 'selectedCategories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading product edit page: ' . $e->getMessage());
        }
    }
    public function update(UpdateProductRequest $request, $slug)
    {
        
        try {
            $product = Product::where('slug', $slug)->firstOrFail();

            if ($request->hasFile('image')) {
                if ($product->image) {
                    $imagePath = 'products/' . $product->image;
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('products', $imageName, 'public');
                $product->image = $imageName;
            }

            $product->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'status' => $request->status ?? 0,
                'description' => $request->description,
            ]);

            $product->categories()->sync($request->category_id);

            return redirect()->route('admin.products.index')
                            ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating product: ' . $e->getMessage())
                        ->withInput();
        }
    }
    public function destroy($slug)
    {
        try {
            $product = Product::where('slug', $slug)->firstOrFail();
            $product->delete(); // Soft delete

            return redirect()->route('admin.products.index')
                            ->with('success', 'Product moved to trash successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error moving product to trash: ' . $e->getMessage());
        }
    }
    public function trashed()
    {
        try {
            // Fetch only soft-deleted products
            $products = Product::onlyTrashed()->with('categories')->latest()->simplePaginate(5);
            return view('pages.admin.products.trashed', compact('products'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading trashed products: ' . $e->getMessage());
        }
    }

    public function restore($slug)
    {
        try {
            $product = Product::onlyTrashed()->where('slug', $slug)->firstOrFail();
            $product->restore();
            return redirect()->route('admin.products.trashed')->with('success', 'Product restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error restoring product: ' . $e->getMessage());
        }
    }

    public function forceDelete($slug)
    {
        try {
            $product = Product::onlyTrashed()->where('slug', $slug)->firstOrFail();

            if ($product->image) {
                $imagePath = 'products/' . $product->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $product->forceDelete(); // Permanent delete

            return redirect()->route('admin.products.trashed')->with('success', 'Product permanently deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error permanently deleting product: ' . $e->getMessage());
        }
    }

   

  

    public function show($slug)
    {
        try {
            $product = Product::with('categories')->where('slug', $slug)->firstOrFail();
            return view('pages.admin.products.show', compact('product'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error showing product: ' . $e->getMessage());
        }
    }



    

   

    public function generateSlug(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
            ]);
            $slug = Str::slug($request->name);
            return response()->json(['slug' => $slug]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error generating slug: ' . $e->getMessage()], 500);
        }
    }

    public function checkUnique(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
            ]);
            $exists = Product::where('name', $request->name)->exists();
            return response()->json(['isUnique' => !$exists]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error checking uniqueness: ' . $e->getMessage()], 500);
        }
    }

    public function checkUniqueForEdit(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
                'product_id' => 'required|exists:products,id',
            ]);
            $exists = Product::where('name', $request->name)
                            ->where('id', '!=', $request->product_id)
                            ->exists();
            return response()->json(['isUnique' => !$exists]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error checking uniqueness: ' . $e->getMessage()], 500);
        }
    }
}