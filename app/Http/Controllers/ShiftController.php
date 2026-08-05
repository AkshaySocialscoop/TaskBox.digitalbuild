<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
    public function index(Request $request)
{
    $query = Shift::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('start_time')) {
        $query->where('start_time', $request->start_time);
    }

    if ($request->filled('end_time')) {
        $query->where('end_time', $request->end_time);
    }

    $shifts = $query->latest()->get();

    return view('super-admin.employee-management.add-shift.index', compact('shifts'));
}

    public function store(Request $request)
    {

        $company_id = auth()->user()->company->id;

        $data = $request->validate([
            'name' => 'required|max:255',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $data['company_id'] = $company_id;
        Shift::create($data);

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
