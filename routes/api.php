<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\AuthController;
use App\Http\Controllers\Apis\OAuth2Controller;
use App\Http\Controllers\Apis\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('google/redirect', [OAuth2Controller::class, 'redirect']);
Route::get('google/callback', [OAuth2Controller::class, 'handleCallback']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    //Task Controller here
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks/create-task', [TaskController::class, 'create']);
    Route::get('/tasks/show/{id}', [TaskController::class, 'show']);
    Route::patch('/tasks/update/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/delete/{id}', [TaskController::class, 'destroy']);
});

