<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\AdminRequest;


class AddAdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Start with base query including the role relationship
            $query = Admin::with('role');
            
            // Search functionality
            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like','%' . $request->search . '%');
                });
                
            }
            
            // Date range filter
            if (!empty($request->date_start)) {
                $query->whereDate('created_at', '>=',$request->date_start);
            }
            if (!empty($request->date_end)) {
                $query->whereDate('created_at', '<=', $request->date_end);
            }
    
            // Pagination
            $admins = $query->latest()->simplePaginate(4);
    
            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pages.admin.admins.partials.admin_table', compact('admins'))->render(),
                    'pagination' => $admins->links('pagination::simple-tailwind')->render(),
                    'success' => true
                ]);
            }
    
            // Regular page load
            return view('pages.admin.admins.index', compact('admins'));
    
        } catch (\Exception $e) {
            // Handle errors
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
            }
    
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function create()
    {
        try {
            $roles = Role::all();
            return view('pages.admin.admins.create', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admins.index')->with('error', 'Failed to load the create admin page. Please try again.');
        }
    }
    

    public function store(AdminRequest $request)
    {
        try {
            $validated = $request->validated();// Hash the password before storing
            $validated['password'] = Hash::make($validated['password']);
            Admin::create($validated);
            return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }
    public function edit($id){
        
        try{
              $admin=Admin::where('id',$id)->firstOrFail();
              $roles=Role::all();
              return view('pages.admin.admins.edit', compact('admin','roles'));
        }catch(\Exception $e){
            return redirect()->back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }
    public function update(AdminRequest $request, $id)
    {
        try {
            $validated = $request->validated();
    
            // Find the admin by ID
            $admin = Admin::findOrFail($id);
    
            // If a new password is provided, hash it before updating
            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']); // Keep existing password if not updated
            }
    
            // Update admin details
            $admin->update($validated);
    
            return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }
    
     
    public function destroy($id){
        try{
            $admin = Admin::where('id', $id)->firstOrFail();
            $admin->delete(); // Soft delete
            
            return redirect()->route('admin.admins.index')
                           ->with('success', 'User moved to trash successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error moving category to trash: ' . $e->getMessage());
        }
        
    }
    public function trashed()
    {
        try {
            $admins = Admin::onlyTrashed()->with('role')->latest()->simplePaginate(4);
            return view('pages.admin.admins.trashed', compact('admins'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading trashed admins: ' . $e->getMessage());
        }
    }
    
    public function restore($id){
        try {
            $admin = Admin::onlyTrashed()->where('id', $id)->firstOrFail();
            $admin->restore();
            return redirect()->route('admin.admins.trashed')->with('success', 'Admin restored successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error restoring admin: ' . $e->getMessage());
        }
    } 
    public function forceDelete($id){
       try{
            $admin = Admin::onlyTrashed()->where('id', $id)->firstOrFail();
            $admin->forceDelete();
            return redirect()->route('admin.admins.trashed')->with('success','Admin Deleted succesfully');
       }catch(\Exception $e){
             return back()->route('admin.admins.trashed')->with('error','Error restoring admin:' .$e->getMessage());
       }
    }

}
