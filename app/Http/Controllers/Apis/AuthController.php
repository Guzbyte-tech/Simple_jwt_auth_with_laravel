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
    public function __construct()
    {
        return $this->middleware(['api'], ["except" => ["login", "register"]]);
    }
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Register a new user",
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Username of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Confirm user password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Registered successfully"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="invalid Credentials"),
     *     @OA\Response(response="400", description="Invalid request"),
     * )
     */

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


    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Authenticate user and generate JWT token",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="invalid Credentials"),
     *     @OA\Response(response="400", description="Invalid request"),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Log the user out",
     *     @OA\Response(response="200", description="User Logout successful"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="Unauthorized request"),
     *     @OA\Response(response="400", description="Invalid request"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function logout()
    {
        auth('api')->logout(true);
        return response([
            "status" => "success",
            "error" => false,
            "message" => "User Logout Successfully",
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/refresh-token",
     *     summary="Refresh the user token",
     *     @OA\Response(response="200", description="Token refreshed"),
     *     @OA\Response(response="400", description="Invalid request"),
     *     @OA\Response(response="401", description="Unauthorized request"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function refreshToken()
    {
        $token = auth('api')->refresh();
        return response([
            "status" => "success",
            "error" => false,
            "message" => "Token refreshed",
            "token" => $token
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
