<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;

///////////////////////////////////////////////////////
////////////////////// Auth routes
Route::post('/auth/sign-up' ,[AuthController::class ,"register"]);
Route::post('/auth/login' ,[AuthController::class ,"login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class , 'logout']);
});


///////////////////////////////////////////////////////
////////////////////// doctors routes
Route::post('/doctor/create' ,[DoctorController:: class, 'create']);