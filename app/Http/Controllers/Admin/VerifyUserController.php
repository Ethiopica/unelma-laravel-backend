<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VerifyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class VerifyUserController extends Controller
{
    public function verifyUser()
    {
        $user = Auth::user();
        //Email verification here
        $link = Crypt::encryptString($user->email);
        $link = url('verify-user/' . $link);
        Mail::to($user->email)->send(new VerifyUser($link));
        return redirect()->route('admin.dashboard')->with('success', 'Verification Link sent successfully, Check Your Mail please');
    }
    public function confirmUser($email)
    {
        // return $email;
        $user_mail = Crypt::decryptString($email);
        $user = User::where('email', $user_mail)->first();
        // return $user[0]->name;
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
            return redirect()->route('admin.dashboard')->with('success', 'User Verified Successfully, Enjoy your work');
        }
    }
}
