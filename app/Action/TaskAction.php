<?php

namespace App\Action;

use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskAction
{

    static function tasks(User $user){
        $tasks = $user->tasks()->get();
        return [
            "status" => "success",
            "error" => false,
            "statusCode" => 200,
            "message" => "List of tasks",
            "data" => $tasks
        ];
    }
    /**
     * Create a new task
     * @param User $user The user that creates the task
     * @param String $title The title of the task
     * @param String $description The description of the task
     * @return Array an array of the new task created 
     *
     */
    static function create(User $user, $title, $description){
        DB::beginTransaction();
        try {
            $task = $user->tasks()->create([
                "title" => $title,
                "description" => $description
            ]);
            DB::commit();
            $response = [
                "status" => "success",
                "statusCode" => 200,
                "error" => false,
                "message" => "Task created successfully",
                "data" => $task
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            $response = [
                "status" => "error",
                "statusCode" => 400,
                "error" => true,
                "message" => "An error occured while creating task. Please try again later"                
            ];
            Log::error("Error creating task ", $ex->getMessage());
            report($ex->getMessage());
        }
        return $response;
    }

    /**
     * Create a new task
     * @param String $id task ID to read
     * @return Array an array of the task
     *
     */
    static function read($id){
        $task = Task::find($id);
        if(is_null($task)){
            $response = [
                "status" => "error",
                "error" => true,
                "statusCode" => 404,
                "message" => "Task not found"
            ];
        }else{
            $response = [
                "status" => "success",
                "error" => false,
                "statusCode" => 200,
                "message" => "Task found",
                "data" => $task
            ];
        }
        return $response;
        
    }

    /**
     * Create a new task
     * @param User $user The user that creates the task
     * @param String $id The Id of the task
     * @param String $title The title of the task
     * @param String $description The description of the task
     * @return Array an array of the new task created 
     *
     */
    static function update(User $user, $id, $title, $description){
        $task = Task::find($id);
        if(is_null($task)){
            return [
                "status" => "error",
                "statusCode" => 404,
                "error" => true,
                "message" => "task not found"
            ];
        }
        if(!self::checkTaskOwnership($user->id, $id)){
            return [
                "status" => "error",
                "statusCode" => 400,
                "error" => true,
                "message" => "task does not belong to you"
            ];
        }
        DB::beginTransaction();
        try {
            $task->update([
                "title" => $title,
                "description" => $description
            ]);
            DB::commit();
            $response = [
                "status" => "success",
                "statusCode" => 200,
                "error" => false,
                "message" => "Task updated successfully",
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            $response = [
                "status" => "error",
                "statusCode" => 400,
                "error" => true,
                "message" => "An error occured while updating task. Please try again later"
            ];
            Log::error("Error creating task ", $ex->getMessage());
            report($ex->getMessage());
        }
        return $response;
    }

    /**
     * Create a new task
     * @param User $user The user that creates the task
     * @param String $id The Id of the task
     *
     */
    static function delete(User $user, $id){
        $task = Task::find($id);
        if(is_null($task)){
            return [
                "status" => "error",
                "statusCode" => 404,
                "error" => true,
                "message" => "task not found"
            ];
        }
        if(!self::checkTaskOwnership($user->id, $id)){
            return [
                "status" => "error",
                "statusCode" => 400,
                "error" => true,
                "message" => "task does not belong to you"
            ];
        }
        
        $task->delete();
        $response = [
            "status" => "success",
            "error" => false,
            "statusCode" => 200,
            "message" => "Task deleted successfully"            
        ];
        return $response;
    }

    /**
     * Check if task belongs to the user
     * @param String $userId The User ID of the user
     * @param String $taskId the task id of the user
     * @return boolean
     */
    static function checkTaskOwnership($userId, $taskId)
    {
        $task = Task::find($taskId);
        if ($task->user_id == $userId) {
            // The task belongs to the user
            return true;
        } else {
            // The task doesn't belong to the user
            return false;
        }
    }

}