<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Service;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $services = Service::orderBy('order')->orderBy('created_at', 'desc')->get();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'image_url'=>['nullable','string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'plans' => ['nullable','array'],
            'plans.*.name'=>['required_with:plans.*','string'],
            'plans.*.price'=>['required_with:plans.*','numeric'],
            'plans.*.period'=>['nullable','string'],
            'plans.*.stripePriceId'=>['required_with:plans.*','string'],
            'plans.*.features'=>['nullable','string'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $service = Service::create($validated);

        
        // instead of creating PlanController, we create related plans for each service here
        if (Schema::hasTable('plans') && !empty($validated['plans'])){
            foreach ($validated['plans'] as $planData){
               $features = [];
                if (!empty($planData['features']) && is_string($planData['features'])) {
                    $features = array_values(array_filter(array_map('trim', explode(',', $planData['features']))));
                }
               
                $service->plans()->create(
                    [
                    'name'=>$planData['name'],
                    'price'=>$planData['price'],
                    'period'=>$planData['period']?? null,
                    'stripe_price_id'=>$planData['stripePriceId']?? null,
                    'features'=>$features,
                    ]);
            }
        }

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service created successfully!');
    }

    /**
     * Show the form for editing a service
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'image_url'=>['nullable','string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'plans' => ['nullable','array'],
            'plans.*.id' => ['nullable','integer'], //plan Id for existing plan
            'plans.*.name'=>['required_with:plans.*','string'],
            'plans.*.price'=>['required_with:plans.*','numeric'],
            'plans.*.period'=>['nullable','string'],
            'plans.*.stripePriceId'=>['required_with:plans.*','string'],
            'plans.*.features'=>['nullable','string'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        // remove plans from validated
        $sentPlansData = $validated['plans'] ?? [];
        unset($validated['plans']);

        // update service, without plans
        $service->update($validated);


        
        //handle plan update
        if (Schema::hasTable('plans') && !empty($sentPlansData)){

            $submittedPlanId = collect($sentPlansData)
            ->pluck('id')->filter()->toArray();

            //when user deletes a plan, plans that don't have id sent will be removed
            $service->plans()->whereNotIn('id',$submittedPlanId)->delete();

            foreach ($sentPlansData as $planData){
                $features = [];
                if (!empty($planData['features']) && is_string($planData['features'])) {
                    $features = array_values(array_filter(array_map('trim', explode(',', $planData['features']))));
                }
                $service->plans()->updateOrCreate(
                    ['id'=>$planData['id']??null],
                    [
                        'name'=>$planData['name'],
                        'price'=>$planData['price'],
                        'period'=>$planData['period']?? null,
                        'stripe_price_id'=>$planData['stripePriceId']?? null,
                        'features'=>$features,
                    ]
                    );
            }
        }
    
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        // Delete image if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted successfully!');
    }
}
