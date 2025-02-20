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
//http://localhost:8000/api/reports/scheduled-calls-date?date=2025-02-18
Route::get('reports/scheduled-calls-date', [ReportController::class, 'getScheduledCallsDate']);
//http://localhost:8000/api/reports/scheduled-calls-pdf-date?date=2025-02-18
Route::get('reports/scheduled-calls-date-pdf', [ReportController::class, 'getScheduledCallsDatePDF']);
//http://localhost:8000/api/reports/patients/4/call-history
Route::get('reports/patients/{id}/call-history', [ReportController::class, 'getPatientCallHistory']);
//http://localhost:8000/api/reports/patients/4/call-history/pdf
Route::get('reports/patients/{id}/call-history/pdf', [ReportController::class, 'getPatientCallHistoryPDF']);
//http://localhost:8000/api/reports/callsUser
Route::get('reports/callsUser',[ReportController::class, 'getCallsUser']);
//http://localhost:8000/api/reports/callsUser/pdf
Route::get('reports/callsUser/pdf', [ReportController::class, 'getUserCallsPDF'])->name('reports.doneCallsPDF');
//http://localhost:8000/api/reports/done-calls?date=2025-02-17
Route::get('reports/done-calls', [ReportController::class, 'getDoneCallsByDate']);
http://localhost:8000/api/reports/done-calls/pdf?date=2025-02-18
Route::get('reports/done-calls/pdf', [ReportController::class, 'getDoneCallsByDatePDF']);
//http://localhost:8000/api/reports/emergencies
//http://localhost:8000/api/reports/emergencies?zone=55
Route::get('reports/emergencies',[ReportController::class, 'getEmergencies' ]);
//http://localhost:8000/api/reports/emergencies/pdf?zone=55
Route::get('reports/emergencies/pdf', [ReportController::class, 'getEmergenciesPDF']);

//Route::apiResource('patients', PatientController::class)->middleware('api');
//Route::get('/test', [OperatorController::class, 'show']);