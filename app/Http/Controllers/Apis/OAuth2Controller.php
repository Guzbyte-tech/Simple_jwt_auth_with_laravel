<?php

namespace App\Http\Controllers\Apis;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class OAuth2Controller extends Controller
{
    public function redirect()
    {
        return response([
            "status" => "success",
            "error" => false,
            "message" => "OAuth2 Initiated successfully, Please redirect user to this url",
            "redirect_to" => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ], 200);
    }

    public function handleCallback()
    {
        
            $googleUser = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('google_id', $googleUser->id)->first();
            $user = User::updateOrCreate([
                'google_id'=> $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'username' => $googleUser->given_name,
                'email' => $googleUser->email,
                'google_id'=> $googleUser->id,
                'password' => Hash::make('123456@dummy'),
                'profile_img' => $googleUser->picture
            ]);
            
            $token = auth('api')->login($user);
            return response()->json([
                "status" => "success",
                "message" => "Login Successful",
                "data" => [
                    "token" => [
                        'access_token' => $token,
                        'token_type' => 'bearer'
                    ],
                    'user' => auth('api')->user()
                ]
            ], 200);
       
    }


}
