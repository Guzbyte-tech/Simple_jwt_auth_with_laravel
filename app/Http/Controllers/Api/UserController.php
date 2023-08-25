<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return response([
            "status" => "success",
            "error" => false,
            "message" => "user details",
            "data" => auth('api')->user()
        ]);
    }
}
