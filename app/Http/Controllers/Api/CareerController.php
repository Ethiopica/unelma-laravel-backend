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
        $apply_data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'CV' => 'required|mimes:pdf|max:10000',
            'cover_letter' => 'required|mimes:pdf,doc,docx|max:10000',
            'career_id' => 'required|exists:careers,id',
        ]);
        
        // Upload CV
        $cv = $apply_data['CV'];
        $cv_name = time() . '_cv_' . $cv->getClientOriginalname();
        $cv_path = $request->file('CV')->storeAs('jobs_applied', $cv_name, 'public');
        
        // Upload Cover Letter
        $cover_letter = $apply_data['cover_letter'];
        $cover_name = time() . '_cover_' . $cover_letter->getClientOriginalname();
        $cover_path = $request->file('cover_letter')->storeAs('jobs_applied', $cover_name, 'public');
        
        if ($cv_path && $cover_path) {
            Career_Apply::create([
                'name' => $request->name,
                'email' => $request->email,
                'career_id' => $request->career_id,
                'user_id' => $request->user()?->id,
                'CV' => $cv_path,
                'cover_letter' => $cover_path,
                'cover_text' => '', // Keep for backward compatibility
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you for applying to us, we will get back to you as soon as possible',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload files. Please try again.',
        ], 500);
    }
}
