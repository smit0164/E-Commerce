<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;

class StaticPagePublicController extends Controller
{
    public function show($slug)
    {
        try {
            // Fetch only active static pages
            $page = StaticPage::where('slug', $slug)
                ->where('status', 'active')
                ->firstOrFail();

            return view('pages.customer.static_pages.static_page', compact('page'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Page not found.');
        }
    }
}
