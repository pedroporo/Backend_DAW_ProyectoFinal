<?php

use App\Http\Controllers\Api\AlertController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OperatorController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\IncomingCallController;
use App\Http\Controllers\Api\OutgoingCallController;
use App\Http\Controllers\Api\CallController;

Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::post('register', [AuthController::class, 'register'])->middleware('api');


Route::middleware(['auth:sanctum', 'api'])->group(function () {

    Route::apiResource('patients', PatientController::class)->middleware('api');
    Route::apiResource('zones', ZoneController::class)->middleware('api');
    Route::apiResource('contacts', ContactController::class)->middleware('api');
    Route::post('/patients/{id}/contacts', [ContactController::class, 'store'])->middleware('api');
    Route::get('/patients/{id}/contacts', [ContactController::class, 'getContactsByPatiente'])->middleware('api');
    Route::get('/zones/{id}/patients', [PatientController::class, 'getPatientsByZone'])->middleware('api');
    Route::get('/zones/{id}/operators', [ZoneController::class, 'getOperators'])->middleware('api');
    Route::apiResource('user', OperatorController::class)->middleware('api');
    Route::post('/user', [OperatorController::class, 'addZone'])->middleware('api');
    Route::post('/user/delZone', [OperatorController::class, 'delZone'])->middleware('api');
    Route::apiResource('alerts', AlertController::class)->middleware('api');

    Route::apiResource('calls', CallController::class)->middleware('api');
    Route::get('patients/{id}/calls', [CallController::class, 'getCallsForPatient'])->middleware('api');
    Route::get('/filter-calls', [CallController::class, 'getFilterCalls']);

    Route::apiResource('incoming-calls', IncomingCallController::class);
    Route::post('incoming-calls', [IncomingCallController::class, 'store']);
    Route::delete('/incoming-calls/{incomingCall}', [IncomingCallController::class, 'destroy']);
    Route::put('/incoming-calls/{incomingCall}', [IncomingCallController::class, 'update']);
    Route::get('incoming-calls/{incomingCall}', [IncomingCallController::class, 'show']);
    
    Route::apiResource('outgoing-calls', OutgoingCallController::class);
    Route::post('outgoing-calls', [OutgoingCallController::class, 'store']);
    Route::delete('/outgoing-calls/{outgoingCall}', [OutgoingCallController::class, 'destroy']);
    Route::put('/outgoing-calls/{outgoingCall}', [OutgoingCallController::class, 'update']);
    Route::get('outgoing-calls/{outgoingCall}', [OutgoingCallController::class, 'show']);
  
    Route::get('reports/patients', [ReportController::class, 'getPatients']);
    Route::get('reports/patientsPDF', [ReportController::class, 'getPatientsPDF']);

    Route::post('logout', [AuthController::class, 'logout']);
});

// Rutas de Informes 
Route::get('reports/scheduled-calls-date', [ReportController::class, 'getScheduledCallsDate']);
Route::get('reports/scheduled-calls-date-pdf', [ReportController::class, 'getScheduledCallsDatePDF']);
Route::get('reports/patients/{id}/call-history', [ReportController::class, 'getPatientCallHistory']);
Route::get('reports/patients/{id}/call-history/pdf', [ReportController::class, 'getPatientCallHistoryPDF']);
Route::get('reports/callsUser',[ReportController::class, 'getCallsUser']);
Route::get('reports/callsUser/pdf', [ReportController::class, 'getUserCallsPDF'])->name('reports.doneCallsPDF');
Route::get('reports/done-calls', [ReportController::class, 'getDoneCallsByDate']);
Route::get('reports/done-calls/pdf', [ReportController::class, 'getDoneCallsByDatePDF']);
Route::get('reports/emergencies',[ReportController::class, 'getEmergencies' ]);
Route::get('reports/emergencies/pdf', [ReportController::class, 'getEmergenciesPDF']);

//Route::apiResource('patients', PatientController::class)->middleware('api');
//Route::get('/test', [OperatorController::class, 'show']);