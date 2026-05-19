<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
     public function index()
    {
        $shifts = Shift::latest()->get();
        return view('super-admin.employee-management.add-shift.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        Shift::create($request->all());

        return back()->with('success', 'Shift created successfully');
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $shift->update($request->all());

        return back()->with('success', 'Shift updated successfully');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return back()->with('success', 'Shift deleted successfully');
    }
}
