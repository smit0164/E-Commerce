<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    
    public function showCategory()
    {
        $categories = Category::all();
        return view('pages.admin.categories', compact('categories'));
    }

    public function generateSlug(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
        ]);
        $slug = Str::slug($request->name);

        return response()->json(['slug' => $slug]);
    }

    public function checkUnique(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
        ]);

        // Removed whereNull('deleted_at') since soft deletes are no longer used
        $exists = Category::where('name', $request->name)->exists();

        return response()->json(['isUnique' => !$exists]);
    }
    public function checkUniqueForEdit(Request $request){
        $categoryId = $request->input('category_id'); // Get category ID from request
        $categoryName = $request->input('name'); // Get category name from request
    
        // Check if another category with the same name exists (excluding the current category)
        $exists = Category::where('name', $categoryName)
                    ->where('id', '!=', $categoryId)
                    ->exists();
    
        return response()->json(['isUnique' => !$exists]); // Return true if unique
    }


    public function destroy(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
            $category = Category::findOrFail($categoryId);
            if ($category->image) {
                $imagePath = 'categories/' . $category->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:categories,name', // Unique check works fine without soft deletes
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'slug' => 'required|string|max:255|unique:categories,slug'  // Unique check works fine without soft deletes
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('categories', $imageName, 'public');
            }

            // Create new category
            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'image' => $imageName ?? null
            ]);
         

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|unique:categories,name,' . $request->category_id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $category = Category::findOrFail($request->category_id);
        $category->name = $request->name;
        $category->slug =$request->slug;
        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($category->image) {
                $imagePath = 'categories/' . $category->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
    
            // Upload the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('categories', $imageName,'public');
            $category->image = $imageName;
        }
    
        $category->save();
       
        return response()->json([
            'success' => true,
            'category' => $category,
            'imageName' => $category->image,
        ]);
    }
    
}