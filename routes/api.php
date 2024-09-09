<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\RequestModelController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\UsersController;
use App\Models\Parameter;
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

Route::post('/login', [UsersController::class, 'login']);
Route::post('/logout', [UsersController::class, 'logout'])->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('/self', [UsersController::class, 'self']);
    Route::get('/entity', [UsersController::class, 'getEntities']);
    Route::put('/update-password', [UsersController::class, 'updatePassword']);
    Route::post('/validate-token', [TokenController::class, 'validateToken']);

    Route::post('/add-request', [RequestModelController::class, 'store']);
    Route::get('/get-my-request', [RequestModelController::class, 'getMyRequest']);
    Route::get('/get-to-me-request', [RequestModelController::class, 'getToMeRequest']);
    Route::put('/requests/{id}/status', [RequestModelController::class, 'changeStatus']);

    Route::post('/scan-code', [AttendanceController::class, 'scanCode']);

    // events 

    Route::get('/events', [EventsController::class, 'getEvents']);
    Route::post('/events-add', [EventsController::class, 'addEvent']);

    // category 

    Route::get('/category/{id}', [CategoryController::class, 'getEventCategory']);

    Route::get('/parameter/{id}', [ParameterController::class, 'getCategoryParameter']);
    Route::get('/get_participants', [ParticipantsController::class, 'getParticipants']);


    Route::post('/score_submit', [ScoreController::class, 'store']);
});
