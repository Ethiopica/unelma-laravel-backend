<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::all();

        return response()->json([
            'success' => true,
            'data' => $careers,
        ]);
    }
}
