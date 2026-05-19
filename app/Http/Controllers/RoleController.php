<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
     // 📄 Show all roles
    public function index()
    {
        $roles = Role::latest()->get();
        return view('super-admin.employee-management.add-role.index', compact('roles'));
    }
    

    // 💾 Store new role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Role created successfully');
    }

    // ✏️ Show edit form
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    // 🔄 Update role
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
        ]);

        $role->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Role updated successfully');
    }

    // ❌ Delete role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return back()->with('success', 'Role deleted successfully');
    }
}
