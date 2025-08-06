<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/tasks',TaskController::class);
    // البروفايل
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::post('/updateprofile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    Route::get('/getprofile',[UserController::class,'getprofile']);
    Route::get('/gettasks',[UserController::class,'gettasks']);
    Route::post('/addTaskToCategory/{id}',[TaskController::class,'addTaskToCategory']);
    Route::get('/getcategories/{id}',[TaskController::class,'getcategories']);
    Route::get('/gettasksbypriority',[TaskController::class,'gettasksbypriority']);
    Route::post('/addtofavourite/{id}',[TaskController::class,'addtofavourite']);
    Route::delete('/deletefromfavourite/{id}',[TaskController::class,'deletefromfavourite']);
    Route::get('/getallfavourites',[TaskController::class,'getallfavourites']);
    // user
    Route::post('/logout',[UserController::class,'logout']);
    Route::put('/user',[UserController::class,'update']);
    Route::get('/user',[UserController::class,'show']);
    Route::delete('/user',[UserController::class,'destroy']);

    // admins
    Route::middleware('checkuser')->group(function () {
        Route::get('/gettasksfromcategory/{id}', [TaskController::class,'gettasksfromcategory']);
        Route::get('/getuser/{id}',[TaskController::class,'getuser']);
        Route::get('/getallprofiles',[ProfileController::class,'getallprofiles']);
        Route::get('/getalltasks',[TaskController::class,'getalltasks']);
        Route::get('/getallusers',[UserController::class,'getallusers']);
    });
});
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
