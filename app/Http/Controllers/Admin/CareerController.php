<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $careers = Career::all();

        return view('admin.careers.index', compact('careers'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.careers.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => 'required'
        ]);

        $service = Career::create($validated);

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job created successfully!');
    }

    /**
     * Show the form for editing a service
     */
    public function edit(Career $career)
    {
        return view('admin.careers.edit', compact('career'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Career $career)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => 'required',
        ]);

        $career->update($validated);

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Career $career)
    {
        $career->delete();
        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job deleted successfully!');
    }
}
