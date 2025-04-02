<?php

// app/Http/Controllers/Admin/StaticPageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\StaticPageRequest;

class StaticPageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = StaticPage::query();

            // Search
            if ($request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Date range
            if ($request->date_start) {
                $query->whereDate('created_at', '>=', $request->date_start);
            }
            if ($request->date_end) {
                $query->whereDate('created_at', '<=', $request->date_end);
            }

            $pages = $query->latest()->simplePaginate(5);

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pages.admin.static_pages.partials.static_pages_table', compact('pages'))->render(),
                    'pagination' => $pages->links('pagination::simple-tailwind')->render(),
                ]);
            }

            return view('pages.admin.static_pages.index', compact('pages'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'An error occurred while fetching static pages.'], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while loading static pages.');
        }
    }


    public function create()
    {
        return view('pages.admin.static_pages.create');
    }

    public function store(StaticPageRequest $request)
    {
        try {
            StaticPage::create($request->validated());
            return redirect()->route('admin.static_pages.index')->with('success', 'Static page created successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to create static page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create the static page.')->withInput();
        }
    }

    public function edit($slug)
    {
        try {
            // Fetch the static page by slug
            $staticPage = StaticPage::where('slug', $slug)->firstOrFail();
    
            // Return the edit view with the static page data
            return view('pages.admin.static_pages.edit', compact('staticPage'));
        } catch (\Exception $e) {
            // Handle the exception (log it and return an error response)
            return redirect()->back()->with('error', 'Static page not found.');
        }
    }
    

    public function update(StaticPageRequest $request, $slug)
    {
        try {
            $staticPage = StaticPage::where('slug', $slug)->firstOrFail();
            $staticPage->update($request->validated());
            return redirect()->route('admin.static_pages.index')->with('success', 'Static page updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to update static page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update the static page.')->withInput();
        }
    }
    

    public function destroy($slug)
    {
        try {
            // Find the static page by slug
            $staticPage = StaticPage::where('slug', $slug)->firstOrFail();
    
            // Delete the static page
            $staticPage->delete();
    
            // Redirect with success message
            return redirect()->route('admin.static_pages.index')->with('success', 'Static page moved to trash.');
        } catch (\Exception $e) {
            // Handle the exception (log it and return an error response)
            return redirect()->back()->with('error', 'Failed to delete the static page.');
        }
    }
    

    public function trashed()
    {
        try {
            // Retrieve only soft-deleted pages
            $pages = StaticPage::onlyTrashed()->latest()->paginate(10);
    
            // Return the trashed pages view
            return view('pages.admin.static_pages.trashed', compact('pages'));
        } catch (\Exception $e) {
            // Handle the exception (log it and return an error response)
            return redirect()->back()->with('error', 'Failed to retrieve trashed static pages.');
        }
    }

    public function restore($slug)
    {
        try {
            // Find the trashed static page by slug
            $staticPage = StaticPage::onlyTrashed()->where('slug', $slug)->firstOrFail();
    
            // Restore the static page
            $staticPage->restore();
    
            return redirect()->route('admin.static_pages.trashed')->with('success', 'Static page restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.static_pages.trashed')->with('error', 'An error occurred while restoring the static page.');
        }
    }
    

    public function forceDelete($slug)
    {
        try {
            // Find the trashed static page by slug
            $page = StaticPage::onlyTrashed()->where('slug', $slug)->firstOrFail();
    
            // Permanently delete the static page
            $page->forceDelete();
    
            return redirect()->route('admin.static_pages.trashed')->with('success', 'Static page permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->route('admin.static_pages.trashed')->with('error', 'An error occurred while deleting the static page.');
        }
    }
    
}