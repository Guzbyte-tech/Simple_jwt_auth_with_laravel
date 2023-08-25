<?php

namespace App\Http\Controllers\Apis;

use App\Action\TaskAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Get user tasks",
     *     description="this endpoint returns all tasks created by the user. remember to pass the Bearer token in the header to authenticate the request.",
    *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="User details retrieved successfully"),
     *     @OA\Response(response="400", description="Invalid request"),
     *     @OA\Response(response="401", description="Unauthorized request"),
     * )
     */
    public function index()
    {
        $result = TaskAction::tasks(auth('api')->user());
        return response($result, $result["statusCode"]);
    }

     /**
     * @OA\Post(
     *     path="/api/v1/tasks/create-task",
     *     summary="Create a new task",
     *     description="This endpoint creates a new task for the user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="The title of the task",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="The description of the task",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Task created successfully"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="unauthorized request"),
     *     @OA\Response(response="400", description="Invalid request"),
     * )
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
     * @OA\Get(
     *     path="/api/v1/tasks/show/{id}",
     *     summary="View a task",
     *     description="This endpoint shows details of a particular task",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task to view",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Task created successfully"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="unauthorized request"),
     *     @OA\Response(response="404", description="Task not found"),
     *     @OA\Response(response="400", description="Invalid request"),
     * )
     */
    public function show(string $id)
    {
        $result = TaskAction::read($id);
        return response($result, $result["statusCode"]); 

    }

    

    /**
     * @OA\Patch(
     *     path="/api/v1/tasks/update/{id}",
     *     summary="Update a task",
     *     description="Call this endpoint to update a task",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task to update",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Task title",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Task description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Task updated successfully"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="unauthorized request"),
     *     @OA\Response(response="404", description="Task not found"),
     *     @OA\Response(response="400", description="Invalid request"),
     * )
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
     * @OA\Delete(
     *     path="/api/v1/tasks/delete/{id}",
     *     summary="Delete a task",
     *     security={{"bearerAuth":{}}},
     *     description="Call this endpoint to delete a task",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task to delete",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Task deleted successfully"),
     *     @OA\Response(response="422", description="Unprocessable entity (validation error)"),
     *     @OA\Response(response="401", description="unauthorized request"),
     *     @OA\Response(response="404", description="Task not found"),
     *     @OA\Response(response="400", description="Invalid request"),
     * )
     */
    public function destroy(string $id)
    {
        $user = auth('api')->user();
        $result = TaskAction::delete($user, $id);
        return response($result, $result["statusCode"]); 
    }
}
