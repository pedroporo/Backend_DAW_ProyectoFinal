<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ZoneController;

Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::post('register', [AuthController::class, 'register'])->middleware('api');


Route::middleware(['auth:sanctum', 'api'])->group(function () {

    Route::apiResource('patients', PatientController::class)->middleware('api');
    Route::apiResource('zones', ZoneController::class)->middleware('api');
    Route::apiResource('contacts', ContactController::class)->middleware('api');
    Route::post('/patients/{id}/contacts', [ContactController::class, 'store'])->middleware('api');
    Route::get('/patients/{id}/contacts', [ContactController::class, 'getContactsByPatiente'])->middleware('api');
    Route::get('/zones/{id}/patients', [PatientController::class, 'getPatientsByZone'])->middleware('api');
    Route::apiResource('zones', PatientController::class);
    Route::get('/zones/{id}/patients', [PatientController::class, 'getPatientsByZone']);
    
    // Rutas de Informes 
    Route::get('reports/emergencies',[ReportController::class, 'getEmergencies' ]);
    Route::get('reports/patients', [ReportController::class, 'getPatients']);
    Route::get('reports/scheduled-calls', [ReportController::class, 'getScheduledCalls']);
    Route::get('reports/done-calls',[ReportController::class, 'getDoneCalls']);
    Route::get('reports/patient-history/{id}', [ReportController::class, 'getPatientHistory']);
    

    Route::post('logout', [AuthController::class, 'logout']);
});
//Route::apiResource('patients', PatientController::class)->middleware('api');