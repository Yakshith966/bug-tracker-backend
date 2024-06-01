<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/roles', [RolesController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{id}/assign', [ProjectController::class, 'assignDevelopers']);

    Route::get('/users', [UserController::class, 'getUsersByRole']);
    Route::apiResource('bugs', BugController::class);
    Route::post('update-bug/{id}', [BugController::class,'updateBug']);
    Route::post('update-status/{id}', [BugController::class,'updateStatus']);
    Route::post('bugs/{id}/assign', [BugController::class, 'assignDevelopers']);
});

Route::get('notifications', [NotificationController::class, 'index']);
Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
Route::get('/bugs/developer/{id}', [BugController::class, 'getBugsForDeveloper']);


