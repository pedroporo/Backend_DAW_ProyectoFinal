<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Zone;
use App\Models\Patient;
use App\Models\User;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    //Listar emergencias asignades al teleoperador
    public function getEmergencies()
    {

        $user = Auth::user();

        if (!$user->zone_id) {
            return response()->json(['Error' => 'No tienes una zona asignada'], 401);
        }

        //Llamadas entrantes consideradas emergencia.
        $emergencyTypes = ['social_emergency', 'medical_emergency', 'loneliness_crisis', 'unanswered_alarm'];

        // Obtener llamadas solo de los pacientes en la zona del operador
        $emergencies = Call::whereIn('incoming_calls_type_enum', $emergencyTypes)
            ->whereHas('patient', function ($query) use ($user) {
                $query->where('zone_id', $user->zone_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($emergencies);
    }

    //Llistar les emergencies per zona
    public function getEmergencyActionsByZone($zoneId)
{
    $user = Auth::user();

    $zoneIds = $user->zones()->pluck('zones.id');
    if (!$zoneIds->contains($zoneId)) {
        return response()->json(['error' => 'Zona no autoritzada'], 403);
    }

    $emergencyTypes = ['social_emergency', 'medical_emergency', 'loneliness_crisis', 'unanswered_alarm'];

    $emergencyCalls = Call::whereIn('incoming_calls_type_enum', $emergencyTypes)
        ->whereHas('patient', function ($query) use ($zoneId) {
            $query->where('zone_id', $zoneId);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($emergencyCalls);
}


    public function getPatients()
    {
        $user = Auth::user();

        $zoneIds = $user->zones()->pluck('zones.id');

        if ($zoneIds->isEmpty) {
            return response()->json(['error' => 'No tienes zonas asignadas'], 401);
        }

        $patients = Patient::whereIn('zone_id', $zoneIds)
            ->orderBy('last_name', 'asc') // Ordenar por apellidos
            ->get();

        return response()->json($patients);
    }

    public function getScheduledCalls(Request $request)
    {
        $user = Auth::user();
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['Error' => 'Debes proporcionar una fecha'], 400);
        }

        $zoneIds = $user->zones()->pluck('zones.id');

        if ($zoneIds->isEmpty()) {
            return response()->json(['error' => 'No tienes zonas asignadas'], 401);
        }

        $scheduledCalls = Call::whereDate('scheduled_at', $date)
            ->whereHas('patient', function ($query) use ($zoneIds) {
                $query->whereIn('zone_id', $zoneIds);
            })
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return response()->json($scheduledCalls);
    }

    public function getDoneCalls(Request $request)
    {
        $user = Auth::user();
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['error' => 'Debes proporcionar una fecha'], 400);
        }

        $zoneIds = $user->zones()->pluck('zones.id');
        if ($zoneIds->isEmpty()) {
            return response()->json(['error' => 'No tienes zonas asignadas'], 401);
        }

        $doneCalls = Call::whereDate('completed_at', $date)
            ->where('user_id', $user->id) // Filtrar llamadas realizadas por el usuario autenticado
            ->whereHas('patient', function ($query) use ($zoneIds) {
                $query->whereIn('zone_id', $zoneIds);
            })
            ->orderBy('completed_at', 'asc')
            ->get();

        return response()->json($doneCalls);
    }

    public function getPatientCallHistory($patientId)
    {
        $user = Auth::user();

        $zoneIds = $user->zones()->pluck('zones.id');

        if ($zoneIds->isEmpty()) {
            return response()->json(['error' => 'No tienes zonas asignadas'], 401);
        }

        $patient = Patient::where('id', $patientId)
            ->whereIn('zone_id', $zoneIds)
            ->first();

        if (!$patient) {
            return response()->json(['error' => 'Este paciente no está en tus zonas'], 403);
        }

        $callHistory = Call::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($callHistory);
    }

    public function getCallHistoryByPatientAndType($patientId, Request $request){
    $user = Auth::user();
    $zoneIds = $user->zones()->pluck('zones.id');

    // Ensure the patient is within the user's zones
    $patient = Patient::where('id', $patientId)
        ->whereIn('zone_id', $zoneIds)
        ->first();

    if (!$patient) {
        return response()->json(['error' => 'Aquest pacient no està en les teves zones'], 403);
    }

    $callType = $request->query('call_type'); // Call type filter

    $query = Call::where('patient_id', $patientId);

    if ($callType) {
        $query->where('incoming_calls_type_enum', $callType);
    }

    $callHistory = $query->orderBy('created_at', 'desc')->get();

    return response()->json($callHistory);
}

}
