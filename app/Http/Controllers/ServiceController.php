<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a service detail page
     */
    public function show($id)
    {
        $service = Service::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('service', compact('service'));
    }
}










