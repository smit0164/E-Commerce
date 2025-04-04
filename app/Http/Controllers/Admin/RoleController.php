<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\RoleRequest;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Role::with('permissions', 'admins');
    
            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }
    
            // Date range filter
            if (!empty($request->date_start)) {
                $query->whereDate('created_at', '>=',$request->date_start);
            }
            if (!empty($request->date_end)) {
                $query->whereDate('created_at', '<=', $request->date_end);
            }
    
            // Paginate results
            $roles = $query->latest()->simplePaginate(4);
    
            // Return AJAX response
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pages.admin.roles.partials.role_table', compact('roles'))->render(),
                    'pagination' => $roles->links('pagination::simple-tailwind')->toHtml(),
                ]);
            }
    
            return view('pages.admin.roles.index', compact('roles'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function create()
    {
        try {
            $permissions = Permission::all();
            return view('pages.admin.roles.create', compact('permissions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create([
                'name' => $request->name,
                'is_super_admin' => ($request->is_super_admin) === 'yes'?'yes':'no',
            ]);

            $role->permissions()->sync($request->permissions);

            return redirect()->route('admin.roles.index')
                           ->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }
    
    public function edit($id) {
        try {
            $role = Role::where('id', $id)->firstOrFail();
            $permissions = Permission::all(); // Assuming you need all permissions
            return view('pages.admin.roles.edit', compact('role', 'permissions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error while loading the edit page: ' . $e->getMessage());
        }
    }
    
    public function update(RoleRequest $request,$id)
    {
       
        try {
            $role=Role::where('id',$id)->firstOrFail();
            $role->update([
                'name' => $request->name,
                'is_super_admin' => ($request->is_super_admin) === 'yes'?'yes':'no',
            ]);

            $role->permissions()->sync($request->permissions);

            return redirect()->route('admin.roles.index')
                           ->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }
    public function destroy($id){
       try{
            $role=Role::where('id',$id)->firstOrfail();
            $role->delete();
            return redirect()->route('admin.roles.index')->with('success',"Role deleted succesfully");
       }catch(\Exception $e){
            return back()->withInput()->with('error','Error updating role: ' . $e->getMessage());
       }
    }

    public function trashed(){
          try{
                $roles=Role::onlyTrashed()->with('admins')->simplePaginate(4);
                return view('pages.admin.roles.trashed',compact('roles'));
          }catch(\Exception $e){
                return back()->withInput()->With('error','Error updating role:' . $e->getMessage());
          }
    }
    public function restore($id){
         try{
               $role=Role::onlyTrashed()->where('id',$id)->firstOrfail();
               $role->restore();
               return redirect()->route('admin.roles.trashed')->with('success','Role restore successfully');
         }catch(\Exception $e){
              return back()->with('error','Error while restoring the role' . $e->getMessage());
         }
    }
    public function forceDelete($id){
          try{
            $role=Role::onlyTrashed()->where('id',$id)->firstOrfail();
            $role->forceDelete();
            return redirect()->route('admin.roles.trashed')->with('success','Role deleted succesfully');
          }catch(\Exception $e){
            return back()->with('error','Error while deleting the role' . $e->getMessage());
          }
    }


}
