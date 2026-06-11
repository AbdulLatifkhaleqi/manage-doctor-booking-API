<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


///////////////////////////////////////////////////////
////////////////////// Auth routes
Route::post('/auth/sign-up' ,[AuthController::class ,"register"]);
Route::post('/auth/login' ,[AuthController::class ,"login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'show']);
});