<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Career_Apply;
use Illuminate\Http\Request;


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
    public function apply(Request $request)
    {

        // return $request->user();
        $apply_data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'CV' => 'required|mimes:pdf|max:10000',
            'cover_letter' => 'required',
        ]);
        $cv = $apply_data['CV'];
        $cv_name = $cv->getClientOriginalname();
        $path = $request->file('CV')->storeAs('jobs_applied', $cv_name, 'public');
        if ($path) {
            Career_Apply::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    "career_id" => 2,
                    // "career_id" => $request->user()->id,
                    'user_id' => 2,
                    // 'user_id' => $request->user()->id,
                    "CV" => $path,
                    'cover_text' => $request->cover_letter,
                ]
            );
            return response()->json([
                'success' => true,
                'message' => 'Thank you for applying to use, we will get back to you as soon as possible',
            ]);
        }
    }
}
