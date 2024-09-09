<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\RequestModelController;
use App\Http\Controllers\ResultController;
use App\Models\Parameter;
use App\Models\Participants;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});


Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/fetch-category-data', [HomeController::class, 'fetchCategoryData']);

    // users 
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::post('/user_add', [UsersController::class, 'store'])->name('user-add');
    Route::post('/user_update', [UsersController::class, 'update'])->name('user-update');
    Route::post('/users/{id}/status', [UsersController::class, 'updateStatus'])->name('users.updateStatus');


    Route::get('/requests', [RequestModelController::class, 'index'])->name('requests');


    // events 
    Route::get('/events', [EventsController::class, 'index'])->name('events');
    Route::post('/events_add', [EventsController::class, 'store'])->name('events-add');
    Route::post('/events/{id}/status', [EventsController::class, 'updateStatus'])->name('events.updateStatus');
    Route::post('/event_update', [EventsController::class, 'update'])->name('event-update');


    // category 
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/category_add', [CategoryController::class, 'store'])->name('category-add');
    Route::post('/category_update', [CategoryController::class, 'update'])->name('category-update');
    Route::post('/category/{id}/status', [CategoryController::class, 'updateStatus'])->name('category.updateStatus');
    Route::delete('/category_delete/{id}', [CategoryController::class, 'delete'])->name('category-delete');


    Route::get('all_category', [CategoryController::class, 'getAll'])->name('all_category');
    // parameter

    Route::post('/parameter_add', [ParameterController::class, 'store'])->name('parameter-add');


    // Participants
    Route::get('/participants', [ParticipantsController::class, 'index'])->name('participants');
    Route::post('/participant_add', [ParticipantsController::class, 'store'])->name('participant-add');
    Route::post('/participant_update', [ParticipantsController::class, 'update'])->name('participant-update');
    Route::post('/participant/{id}/status', [ParticipantsController::class, 'updateStatus'])->name('participant.updateStatus');
    Route::delete('/participant_delete/{id}', [ParticipantsController::class, 'delete'])->name('participant-delete');


    // result 

    Route::get('/results', [ResultController::class, 'index'])->name('results');
    Route::post('/calculate', [ResultController::class, 'calculate'])->name('calculate');
    Route::post('/calculate-all', [ResultController::class, 'calculateAllCategories'])->name('calculate-all');

    // Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse');
    // Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

    // Route::get('/files', [AttendanceController::class, 'index'])->name('attendance');


    // warehouse 
    // Route::get('/warehouse/items', [WarehouseController::class, 'getItems']);
    // Route::post('/warehouse_add', [WarehouseController::class, 'store'])->name('warehouse-add');
    // Route::post('/warehouse_update', [WarehouseController::class, 'update'])->name('warehouse-update');
    // Route::post('/update-warehouse-quantities', [WarehouseController::class, 'updateWarehouseQuantities'])->name('update-warehouse-quantities');

    // // users 
    // Route::get('/users', [UsersController::class, 'index'])->name('users');

    // Route::post('/user_add', [UsersController::class, 'store'])->name('user-add');
    // Route::post('/user_update', [UsersController::class, 'update'])->name('user-update');
    // Route::post('/users/{id}/status', [UsersController::class, 'updateStatus'])->name('users.updateStatus');
    // // cashier 
    // Route::get('/cashier', [InventoryController::class, 'cashier'])->name('cashier');
    // Route::get('/cashier-search', [InventoryController::class, 'searchAvailable'])->name('cashier-search');

    // // payment 

    // Route::post('/payment', [PaymentController::class, 'store'])->name('payment');

    // // class 
    // Route::get('/class', [ClassSetController::class, 'index'])->name('class');
    // Route::post('/class_add', [ClassSetController::class, 'store'])->name('class-add');
    // Route::post('/class/{id}/status', [ClassSetController::class, 'updateStatus'])->name('class.updateStatus');

    // // filemanager 
    // // Route::post('/upload', [FileManagerController::class, 'store'])->name('upload');
    // // Route::delete('/file/{id}', [FileManagerController::class, 'destroy'])->name('file.destroy');

    // // classrooms 

    // Route::get('/room', [ClassroomController::class, 'index'])->name('room');
    // Route::post('/room_add', [ClassroomController::class, 'store'])->name('room-add');
    // Route::post('/room_edit', [ClassroomController::class, 'update'])->name('room-edit');
    // Route::post('/room_delete', [ClassroomController::class, 'delete'])->name('room_delete');
    // Route::post('/room/{id}/status', [ClassroomController::class, 'updateStatus'])->name('room.updateStatus');
    // Route::get('/room/{id}', [ClassroomController::class, 'show'])->name('pages.room.show');


    // // exam 
    // Route::get('/exam', [ExamBuilderController::class, 'index'])->name('exam');
    // Route::post('/exam_add', [ExamBuilderController::class, 'store'])->name('exam-add');
    // Route::post('/exam_edit', [ExamBuilderController::class, 'update'])->name('exam-edit');
    // Route::post('/exam_delete', [ExamBuilderController::class, 'delete'])->name('exam_delete');
    // Route::post('/exam/{id}/status', [ExamBuilderController::class, 'updateStatus'])->name('exam.updateStatus');


    // Route::post('/user_class/add', [ClassroomUserClasssetController::class, 'store'])->name('user_class_add');
});
