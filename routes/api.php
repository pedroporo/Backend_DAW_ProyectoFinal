<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;

Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::post('register', [AuthController::class, 'register'])->middleware('api');


Route::middleware(['auth:sanctum', 'api'])->group(function () {

    Route::apiResource('patients', PatientController::class)->middleware('api');
    Route::post('logout', [AuthController::class, 'logout']);
});
//Route::apiResource('patients', PatientController::class)->middleware('api');