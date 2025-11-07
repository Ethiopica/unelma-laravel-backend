<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carrer;
use Illuminate\Http\Request;

class CarrerController extends Controller
{
    public function index()
    {
        $carrers = Carrer::all();
        return response()->json([
            'success' => true,
            'data' => $carrers,
        ]);
    }
}
