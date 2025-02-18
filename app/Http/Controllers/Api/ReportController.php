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
use App\Enums\Alarms_type;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    //Listar emergencias asignades al teleoperador

    /**
     * @OA\Get(
     *     path="/api/emergencies",
     *     summary="List emergencies assigned to the operator",
     *     tags={"Emergencies"},
     *     @OA\Response(
     *         response=200,
     *         description="List of emergency calls",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Call"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User does not have an assigned zone",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/emergencies/{zoneId}",
     *     summary="List emergencies by zone",
     *     tags={"Emergencies"},
     *     @OA\Parameter(
     *         name="zoneId",
     *         in="path",
     *         required=true,
     *         description="Zone ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of emergency calls for the given zone",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Call"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized zone",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="List patients assigned to the operator's zones",
     *     tags={"Patients"},
     *     @OA\Response(
     *         response=200,
     *         description="List of patients",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Patient"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User does not have assigned zones",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function getPatients()
    {
        //Recoger los pacientes que tenga asignado
        // el teleoperador y ordenarlos por apellido

        // Obtener el usuario autenticado (asumiendo que usas autenticación con Laravel)

        // Obtener las zonas asociadas al usuario con ID 9 cambiar a $user = auth()->user(); esto es solo de prueba
        $user = \App\Models\User::find(2);
        $zonesIds = $user->zones()->pluck('zones.id'); // Obtiene los IDs de las zonas

        // Obtener los pacientes que estén en las zonas asociadas al usuario
        $patients = \App\Models\Patient::whereIn('zone_id', $zonesIds)->orderBy('last_name', 'asc')->get();

        return response()->json($patients);
    }

    public function getPatientsPDF()
    {
        // Obtener las zonas asociadas al usuario con ID 9
        $user = User::find(2);
        $zonesIds = $user->zones()->pluck('zones.id'); // Obtiene los IDs de las zonas asociadas al usuario
    
        // Obtener los pacientes que estén en las zonas asociadas al usuario
        $patients = Patient::whereIn('zone_id', $zonesIds)->orderBy('last_name', 'asc')->get();
    
        // Pasar el nombre del teleoperador a la vista
        $operatorName = $user->name; // O el campo que almacene el nombre del teleoperador
    
        // Cargar la vista y generar el PDF
        $pdf = Pdf::loadView('pdf.patients', ['patients' => $patients, 'operatorName' => $operatorName]);
    
        // Devolver el PDF como descarga
        return $pdf->download('patients_list.pdf');
    }
    


    /**
     * @OA\Get(
     *     path="/api/scheduled-calls",
     *     summary="List scheduled calls for a specific date",
     *     tags={"Calls"},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="Date to filter calls",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of scheduled calls",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Call"))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Date parameter is required",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
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

    public function getCallHistoryByPatientAndType($patientId, Request $request)
    {
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
