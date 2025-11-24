<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsletterSubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        //Login so that we can access, unelma Mail from our credentials
        $Admin_email = env('UNELMA_EMAIL');
        $Admin_password = env('UNELMA_PASSWORD');
        //For Users external customers
        $validated = $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'The email field is required',
            'email.email' => 'This should be valid email',
        ]);
        $user_email = $validated['email'];

        //For admins first logged in to unelmaMail
        $login_admin_unelmamail = Http::post('https://core.unelmamail.com/api/v1/user/login', [
            'email' => $Admin_email,
            'password' => $Admin_password,
        ]);
        $response = json_decode($login_admin_unelmamail->body());
        $token = $response->api_token;

        // dd('working till here');
        // dd($user_email);

        //Now add siiuscriber
        $add_subscriber = Http::withHeaders([
            'api_token' =>  $token
        ])->post('https://core.unelmamail.com/api/v1/subscribers', [
            'list_uid' => '6914039d88fe9',
            'email' => $user_email,
        ]);
        var_export($add_subscriber->body());

        // return $data;
    }
}
