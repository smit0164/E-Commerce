<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticBlock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Admin\StaticBlockRequest;
use App\Http\Requests\Admin\StaticBlockUpdateRequest;

class StaticBlockController extends Controller
{
    public function index()
    {
        try {
        $blocks = StaticBlock::latest()->simplePaginate(5); // Sorts by created_at DESC and paginates
        return view('pages.admin.static_blocks.index', compact('blocks'));
        }catch (\Exception $e) {
            return back()->with('error', 'Error loading static_blocks: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pages.admin.static_blocks.create');
    }

    public function store(StaticBlockRequest $request)
    {
        
    try{ 
           
            $data = [
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'content' => $request->input('content'),
                'is_active' => $request->boolean('is_active'),
            ];

            StaticBlock::create($data);
            return redirect()->route('admin.static_blocks.index')->with('success', 'Block created successfully');
        }catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to create block. Please try again.')->withInput();
        }
    }

    public function edit(StaticBlock $id)
    {
        $staticBlock = $id;
        return view('pages.admin.static_blocks.edit', compact('staticBlock'));
    }

    public function update(StaticBlockUpdateRequest $request, StaticBlock $id)
    {
        try {
        

            // Prepare data for update
            $data = [
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'content' => $request->input('content'),
                'is_active' => $request->boolean('is_active'),
            ];

            // Update the static block
            $id->update($data);

            // Redirect with success message
            return redirect()->route('admin.static_blocks.index')->with('success', 'Block updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update block. Please try again.')->withInput();
        }
    }

    public function destroy(StaticBlock $id)
    {
        $id->delete();
        return redirect()->route('admin.static_blocks.index')->with('success', 'Block deleted successfully');
    }


    public function generateSlug(Request $request)
    {
        try {
            $slug = Str::slug($request->title);
            return response()->json(['slug' => $slug]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error generating slug: ' . $e->getMessage()], 500);
        }
    }
}
