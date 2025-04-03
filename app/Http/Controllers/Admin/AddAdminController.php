<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AddAdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            $admins = Admin::with('role')->paginate(10);

            // if ($request->ajax()) {
            //     return [
            //         'html' => view('pages.admin.admins.partials.admin_table', compact('admins'))->render(),
            //         'pagination' => $admins->links('pagination::simple-tailwind')->render()
            //     ];
            // }

            return view('pages.admin.admins.index', compact('admins'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function create()
    {
        $roles = Role::all();
        return view('pages.admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => 'active' // Default status
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully');
    }

}
