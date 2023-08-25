<?php

namespace App\Http\Controllers\Apis;

use App\Action\TaskAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = TaskAction::tasks(auth('api')->user());
        return response($result, $result["statusCode"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required"
        ]);
        if($validator->fails()){
            return response([
                "status" => "error",
                "error" => true,
                "message" => $validator->errors()
            ]);
        }
        $result = TaskAction::create(auth('api')->user(), $request->title, $request->description);
        return response($result, $result["statusCode"]); 
    }

   
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = TaskAction::read($id);
        return response($result, $result["statusCode"]); 

    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required"
        ]);
        if($validator->fails()){
            return response([
                "status" => "error",
                "error" => true,
                "message" => $validator->errors()
            ]);
        }
        $user = auth('api')->user();
        $result = TaskAction::update($user, $id, $request->title, $request->description);
        return response($result, $result["statusCode"]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth('api')->user();
        $result = TaskAction::delete($user, $id);
        return response($result, $result["statusCode"]); 
    }
}
