<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Carrer;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $carrers = Career::all();

        return view('admin.carrers.index', compact('carrers'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.carrers.create');
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
            ->route('admin.carrers.index')
            ->with('success', 'Job created successfully!');
    }

    /**
     * Show the form for editing a service
     */
    public function edit(Career $carrer)
    {
        return view('admin.carrers.edit', compact('carrer'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Career $carrer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => 'required',
        ]);

        $carrer->update($validated);

        return redirect()
            ->route('admin.carrers.index')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Career $carrer)
    {
        $carrer->delete();
        return redirect()
            ->route('admin.carrers.index')
            ->with('success', 'Job deleted successfully!');
    }
}
