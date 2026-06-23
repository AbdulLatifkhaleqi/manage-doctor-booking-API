<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Middleware\AdminAuth;

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
Route::post('/doctors/create' ,[DoctorController:: class, 'create']);
Route::get('/doctors' , [DoctorController::class ,'doctors']);

Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);
Route::put('/doctors/{id}', [DoctorController::class, 'update']);

///////////////////////////////////////////////////////
////////////////////// doctors routes
Route::post('/appointment/book', [AppointmentController::class, 'appointmentBook'])
    ->middleware('auth:sanctum');
    
Route::get('/appointments', [AppointmentController::class, 'getUserAppointments'])
    ->middleware('auth:sanctum');

Route::get('/appointment/cancelAppoint/{id}', [AppointmentController::class, 'cancelAppointment'])
    ->middleware('auth:sanctum');

Route::get('/admin/appointments', [AppointmentController::class, 'Appointments']);


Route::post('/admin/login', [AdminController::class, 'login'])
    ->middleware(AdminAuth::class);