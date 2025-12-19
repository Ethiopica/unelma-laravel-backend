<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VerifyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerifyUserController extends Controller
{
    /**
     * Send verification email to the authenticated user
     */
    public function sendVerificationEmail()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Please login to verify your email.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->back()->with('info', 'Your email is already verified.');
        }

        try {
            // Generate encrypted verification link
            $encryptedEmail = Crypt::encryptString($user->email);
            $verificationLink = url('verify-user/' . $encryptedEmail . '/confirm');
            
            // Send verification email
            Mail::to($user->email)->send(new VerifyUser($verificationLink));
            
            Log::info('Verification email sent', ['user_id' => $user->id, 'email' => $user->email]);
            
            return redirect()->back()->with('success', 'Verification link sent successfully! Please check your email.');
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()->with('error', 'Failed to send verification email. Please try again later.');
        }
    }

    /**
     * Confirm user email verification from the link
     */
    public function confirmUser($encryptedEmail)
    {
        try {
            $email = Crypt::decryptString($encryptedEmail);
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return redirect()->route('admin.login')->with('error', 'Invalid verification link.');
            }

            if ($user->email_verified_at) {
                return redirect()->route('admin.dashboard')->with('info', 'Your email is already verified.');
            }

            $user->email_verified_at = now();
            $user->save();
            
            Log::info('User email verified', ['user_id' => $user->id, 'email' => $user->email]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Email verified successfully! Welcome aboard.');
        } catch (\Exception $e) {
            Log::error('Email verification failed', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.login')->with('error', 'Invalid or expired verification link.');
        }
    }
}
