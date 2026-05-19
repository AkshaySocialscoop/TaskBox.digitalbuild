<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
     public function index()
    {
        $departments = Department::latest()->get();
        return view('super-admin.employee-management.add-department.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name|max:255',
        ]);

        Department::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Department created successfully');
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:departments,name,' . $id,
        ]);

        $department->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Department updated successfully');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return back()->with('success', 'Department deleted ');
    }
}
