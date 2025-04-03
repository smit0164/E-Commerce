<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;


class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::with('permissions')->paginate(10);
            return view('pages.admin.roles.index', compact('roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
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
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'is_super_admin' => 'nullable|in:yes', // Only accepts 'yes' or null
            'permissions' => 'array|nullable'
        ]);
    
        $role = Role::create([
            'name' => $request->name,
            'is_super_admin' => $request->is_super_admin === 'yes' ? 'yes' : 'no'
        ]);
    
        if ($request->permissions) {
            $role->permissions()->sync($request->permissions);
        }
    
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

}
