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
    public function index(Request $request)
{
    try {
        $query = StaticBlock::query();

        // Search by title or slug
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by creation date range
        if (!empty($request->date_start)) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }

        if (!empty($request->date_end)) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $blocks = $query->latest()->simplePaginate(5);

        // If AJAX request, return rendered HTML and updated pagination
        if ($request->ajax()) {
            return response()->json([
                'html'       => view('pages.admin.static_blocks.partials.static_blocks_table', compact('blocks'))->render(),
                'pagination' => (string) $blocks->links('pagination::simple-tailwind'),
            ]);
        }

        return view('pages.admin.static_blocks.index', compact('blocks'));
    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
        return redirect()->back()->with('error', 'Something went wrong!');
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
                'is_active' => $request->input('is_active'),
            ];

            StaticBlock::create($data);
            return redirect()->route('admin.static_blocks.index')->with('success', 'Block created successfully');
        }catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to create block. Please try again.')->withInput();
        }
    }

    public function edit($slug)
    {
        try{
            $staticBlock=StaticBlock::where('slug',$slug)->firstOrFail();
            return view('pages.admin.static_blocks.edit', compact('staticBlock'));
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to Load edit Page. Please try again.');
        }
       
    }

    public function update(StaticBlockRequest $request,  $slug)
    {
        try {
            $staticBlock = StaticBlock::where('slug', $slug)->firstOrFail();

            $data = [
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'content' => $request->input('content'),
                'is_active' => $request->input('is_active') === 'active' ? true : false, // Convert to boolean
            ];
    
            // Update the static block
            $staticBlock->update($data);
    
            // Redirect with success message
            return redirect()->route('admin.static_blocks.index')->with('success', 'Block updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update block: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($slug)
   {    
        try{
            $staticBlock =StaticBlock::where('slug', $slug)->firstOrFail();
            $staticBlock->delete();
            return redirect()->route('admin.static_blocks.index')
                            ->with('success', 'Block moved to trash successfully');
        }catch (\Exception $e){
            return back()->with('error', 'Error loading trashed static blocks: ' . $e->getMessage());
        }
    }
   public function trashed()
   {
       try {
           $staticBlocks = StaticBlock::onlyTrashed()->latest()->simplePaginate(5);
           return view('pages.admin.static_blocks.trashed', compact('staticBlocks'));
       } catch (\Exception $e) {
           return back()->with('error', 'Error loading trashed static blocks: ' . $e->getMessage());
       }
   }
   
    public function restore($slug){
        try {
            $staticBlock=StaticBlock::onlyTrashed()->where('slug', $slug)->firstOrFail();
            $staticBlock->restore();
            return redirect()->route('admin.static_blocks.trashed')->with('success', 'Block restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error restoring block: ' . $e->getMessage());
        }
    }
    
    public function forceDelete($slug)
    {
        try {
            $staticBlock=StaticBlock::onlyTrashed()->where('slug', $slug)->firstOrFail();
            $staticBlock->forceDelete(); // Permanent delete
            return redirect()->route('admin.static_blocks.trashed')->with('success', 'Block permanently deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error permanently deleting block: ' . $e->getMessage());
        }
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
