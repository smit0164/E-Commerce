<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            // Fetch only non-deleted categories
            $categories = Category::latest()->simplePaginate(5);
            return view('pages.admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading categories: ' . $e->getMessage());
        }
    }

    public function trashed()
    {
        
        try {
            // Fetch only soft-deleted categories
            $categories = Category::onlyTrashed()->latest()->simplePaginate(5);
            return view('pages.admin.categories.trashed', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading trashed categories: ' . $e->getMessage());
        }
    }
    
    public function restore($slug)
    {
        try {
            $category = Category::onlyTrashed()->where('slug', $slug)->firstOrFail();
            $category->restore();
            return redirect()->route('admin.categories.trashed')->with('success', 'Category restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error restoring category: ' . $e->getMessage());
        }
    }

    public function forceDelete($slug)
    {
        try {
            $category = Category::onlyTrashed()->where('slug', $slug)->firstOrFail();
            
            if ($category->image) {
                $imagePath = 'categories/' . $category->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            
            $category->forceDelete(); // Permanent delete
            
            return redirect()->route('admin.categories.trashed')->with('success', 'Category permanently deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error permanently deleting category: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pages.admin.categories.create');
    }

    public function store(Request $request)
    {
       
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'slug' => 'required|string|max:255|unique:categories,slug',
                'status' => 'required|in:active,inactive', // Added status validation
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('categories', $imageName, 'public');
            }

            Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'image' => $imageName ?? null,
                'status' => $request->status, // Added status to the create method
            ]);

            return redirect()->route('admin.categories.index')
                           ->with('success', 'Category created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Validation failed: ' . $e->getMessage())
                        ->withErrors($e->errors())
                        ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating category: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show($slug)
    {
        try {
            $category = Category::where('slug', $slug)->firstOrFail();
            $products = $category->products;
            return view('pages.admin.categories.show', compact('category', 'products'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error showing category: ' . $e->getMessage());
        }
    }

    public function edit($slug)
    {
        try {
            $category = Category::where('slug', $slug)->firstOrFail();
            return view('pages.admin.categories.edit', compact('category'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading category edit page: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $slug)
    {
        try {
           
            $category = Category::where('slug', $slug)->firstOrFail();

            $request->validate([
                'name' => 'required|string|min:3|unique:categories,name,' . $category->id,
                'slug' => 'required|string|min:3|unique:categories,slug,' . $category->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:active,inactive',
            ]);

            if ($request->hasFile('image')) {
                if ($category->image) {
                    $imagePath = 'categories/' . $category->image;
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
                $imageName = time() . '.' . $request->image->extension();
                $request->image->storeAs('categories', $imageName, 'public');
                $category->image = $imageName;
            }
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'status'=>$request->status
            ]);

            return redirect()->route('admin.categories.index')
                           ->with('success', 'Category updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Validation failed: ' . $e->getMessage())
                        ->withErrors($e->errors())
                        ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating category: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy($slug)
    {
        try {
            $category = Category::where('slug', $slug)->firstOrFail();
            $category->delete(); // Soft delete
            
            return redirect()->route('admin.categories.index')
                           ->with('success', 'Category moved to trash successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error moving category to trash: ' . $e->getMessage());
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
            $exists = Category::where('name', $request->name)->exists();
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
                'category_id' => 'required|exists:categories,id'
            ]);
            $exists = Category::where('name', $request->name)
                            ->where('id', '!=', $request->category_id)
                            ->exists();
            return response()->json(['isUnique' => !$exists]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error checking uniqueness: ' . $e->getMessage()], 500);
        }
    }
}