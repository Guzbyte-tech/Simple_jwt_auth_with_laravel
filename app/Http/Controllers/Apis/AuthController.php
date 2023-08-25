<?php

namespace App\Http\Controllers\Apis;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => 'required|exists:users,email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response([
                "status" => "error",
                "error" => true,
                "message" => $validator->errors()
            ], 422);
        }
        $credentials = $request->only('email', 'password');
        if (!$token = auth('api')->attempt($credentials)){
            return response([
                "status" => "error",
                "error" => true,
                "message" => "Unauthorized"
            ], 401);
        }
        return $this->createToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'unique:users,username'],
            'name' => ['required', 'unique:users,username'],
            "email" => ["required", "email:rfc,dns", "unique:users"],
            "password" => ["required", 'confirmed', Password::min(8)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols()
            ->uncompromised()],
        ]);
        if($validator->fails()){
            return response([
                "status" => "error",
                "error" => true,
                "message" => $validator->errors()
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $credentials = $request->only('email', 'password');
        return response([
            'message' => 'User created successfully',
            'user' => $user,
        ], 200);
    }
    
    protected function createToken($token)
    {
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
