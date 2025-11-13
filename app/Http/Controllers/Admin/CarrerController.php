<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrer;
use Illuminate\Http\Request;

class CarrerController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $carrers = Carrer::all();

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
        ]);

        $service = Carrer::create($validated);

        return redirect()
            ->route('admin.carrers.index')
            ->with('success', 'Job created successfully!');
    }

    /**
     * Show the form for editing a service
     */
    public function edit(Carrer $carrer)
    {
        return view('admin.carrers.edit', compact('carrer'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Carrer $carrer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $carrer->update($validated);

        return redirect()
            ->route('admin.carrers.index')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Carrer $carrer)
    {
        $carrer->delete();

        return redirect()
            ->route('admin.carrers.index')
            ->with('success', 'Job deleted successfully!');
    }
}
