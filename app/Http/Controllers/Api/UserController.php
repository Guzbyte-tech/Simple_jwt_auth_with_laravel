<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Get user Details",
     *     description="This endpoint returns the full details of the authenticated user.",
     *     @OA\Response(response="200", description="User details retrieved successfully"),
     *     @OA\Response(response="400", description="Invalid request"),
     *     @OA\Response(response="401", description="Unauthorized request"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index(){
        return response([
            "status" => "success",
            "error" => false,
            "message" => "user details",
            "data" => auth('api')->user()
        ]);
    }
}
