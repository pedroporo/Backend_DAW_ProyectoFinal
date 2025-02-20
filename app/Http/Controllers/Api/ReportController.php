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

    /**
     * @OA\Get(
     *     path="/api/reports/patients",
     *     summary="Obtener lista de pacientes asignados al teleoperador",
     *     tags={"Reportes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Patient")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado"
     *     )
     * )
     */
    public function getPatients()
    {
        $user = auth()->user() ?? User::find(2);
        $zonesIds = $user->zones()->pluck('zones.id'); // Obtiene los IDs de las zonas
        $patients = \App\Models\Patient::whereIn('zone_id', $zonesIds)->orderBy('last_name', 'asc')->get();

        return response()->json($patients);
    }

    /**
 * @OA\Get(
 *     path="/api/reports/patients/pdf",
 *     summary="Generar PDF con la lista de pacientes asignados",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="PDF generado correctamente de la lista de pacientes",
 *         @OA\JsonContent(type="string", example="patients_list.pdf")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getPatientsPDF()
    {
        $user = auth()->user() ?? User::find(2);
        $zonesIds = $user->zones()->pluck('zones.id');
        $patients = Patient::whereIn('zone_id', $zonesIds)->orderBy('last_name', 'asc')->get();
        $operatorName = $user->name;
        $pdf = Pdf::loadView('pdf.patients', ['patients' => $patients, 'operatorName' => $operatorName]);
        return $pdf->download('patients_list.pdf');
    }


    /**
 * @OA\Get(
 *     path="/api/reports/done-calls",
 *     summary="Obtener llamadas realizadas (salientes) por fecha",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="Fecha en formato YYYY-MM-DD",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2025-02-18")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de llamadas realizadas por el usuario",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="date", type="string", example="2025-02-18"),
 *             @OA\Property(
 *                 property="outgoing_calls",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/OutgoingCall")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getDoneCallsByDate(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $outgoingCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'date' => $date,
            'outgoing_calls' => $outgoingCalls
        ]);
    }

    
    /**
 * @OA\Get(
 *     path="/api/reports/done-calls/pdf",
 *     summary="Generar PDF de llamadas realizadas por fecha",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="Fecha en formato YYYY-MM-DD",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2025-02-18")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="PDF generado de llamadas realizadas por fecha",
 *         @OA\JsonContent(
 *             type="string",
 *             example="llamadas_realizadas_2025-02-18.pdf"
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getDoneCallsByDatePDF(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        $outgoingCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', 'desc')
            ->get();

        $pdf = PDF::loadView('pdf.callsDoneDate', [
            'operatorName' => $user->name,
            'date' => $date,
            'outgoingCalls' => $outgoingCalls,
        ]);

        return $pdf->download("llamadas_realizadas_{$date}.pdf");
    }


    /**
 * @OA\Get(
 *     path="/api/reports/scheduled-calls-pdf",
 *     summary="Generar PDF de llamadas planificadas",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="PDF generado de llamadas planificadas",
 *         @OA\JsonContent(
 *             type="string",
 *             example="llamadas_planificadas.pdf"
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getScheduledCallsPDF(Request $request)
    {
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $scheduledCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->where('is_planned', 1)
            ->orderBy('timestamp', 'asc')
            ->get();
        $pdf = PDF::loadView('pdf.callsScheduled', ['user' => $user, 'scheduledCalls' => $scheduledCalls]);
        return $pdf->download('llamadas_planificadas.pdf');
    }



    /**
 * @OA\Get(
 *     path="/api/reports/scheduled-calls-date",
 *     summary="Obtener llamadas planificadas por fecha",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="Fecha en formato YYYY-MM-DD",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2025-02-18")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de llamadas planificadas por fecha",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="date", type="string", example="2025-02-18"),
 *             @OA\Property(
 *                 property="scheduled_calls",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/OutgoingCall")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getScheduledCallsDate(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        $scheduledCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->where('is_planned', 1)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json([
            'date' => $date,
            'scheduled_calls' => $scheduledCalls
        ]);
    }

/**
 * @OA\Get(
 *     path="/api/reports/scheduled-calls-date-pdf",
 *     summary="Obtener PDF de llamadas planificadas por fecha",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="Fecha en formato YYYY-MM-DD",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2025-02-18")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="PDF con las llamadas planificadas por fecha",
 *         @OA\MediaType(
 *             mediaType="application/pdf",
 *             @OA\Schema(type="string", format="binary")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getScheduledCallsDatePDF(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        $scheduledCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->where('is_planned', 1)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', 'asc')
            ->get();

        $pdf = PDF::loadView('pdf.callsScheduledDate', [
            'scheduledCalls' => $scheduledCalls,
            'date' => $date,
        ]);

        return $pdf->download('scheduled_calls_' . $date . '.pdf');
    }

    /**
 * @OA\Get(
 *     path="/api/reports/callsUser",
 *     summary="Obtener todas las llamadas del usuario",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Llamadas entrantes y salientes del usuario",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="incoming_calls",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/IncomingCall")
 *             ),
 *             @OA\Property(
 *                 property="outgoing_calls",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/OutgoingCall")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getCallsUser()
    {
        $user = auth()->user() ?? User::find(2);
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $now = now();
        $incomingCalls = \App\Models\IncomingCall::where('user_id', $user->id)
            ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
            ->orderBy('timestamp', 'desc')
            ->get();

        $outgoingCalls = \App\Models\OutgoingCall::where('user_id', $user->id)
            ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls,
        ]);
    }


    /**
 * @OA\Get(
 *     path="/api/reports/callsUser/pdf",
 *     summary="Obtener todas las llamadas del usuario en formato PDF",
 *     tags={"Reportes"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Archivo PDF con las llamadas realizadas (entrantes y salientes) del usuario",
 *         @OA\MediaType(
 *             mediaType="application/pdf"
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     )
 * )
 */
    public function getUserCallsPDF()
    {
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $now = now();
        $incomingCalls = \App\Models\IncomingCall::where('user_id', $user->id)
            ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
            ->orderBy('timestamp', 'desc')
            ->get();

        $outgoingCalls = \App\Models\OutgoingCall::where('user_id', $user->id)
            ->where('timestamp', '<=', $now)
            ->orderBy('timestamp', 'desc')
            ->get();

        $pdf = PDF::loadView('pdf.callsDone', ['operatorName' => $user->name], [
            'incomingCalls' => $incomingCalls,
            'outgoingCalls' => $outgoingCalls,
        ]);
        return $pdf->download('llamadas_realizadas.pdf');
    }


    /**
 * @OA\Get(
 *     path="/api/reports/patients/{id}/call-history",
 *     summary="Obtener el historial de llamadas de un paciente",
 *     tags={"Reportes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del paciente",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Historial de llamadas del paciente con llamadas entrantes y salientes",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="patient", type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="last_name", type="string")
 *             ),
 *             @OA\Property(property="incoming_calls", type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="timestamp", type="string"),
 *                     @OA\Property(property="details", type="string")
 *                 )
 *             ),
 *             @OA\Property(property="outgoing_calls", type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="timestamp", type="string"),
 *                     @OA\Property(property="details", type="string")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Paciente no encontrado"
 *     )
 * )
 */
    public function getPatientCallHistory($patientId)
    {
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        $incomingCalls = \App\Models\IncomingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        $outgoingCalls = \App\Models\OutgoingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'last_name' => $patient->last_name
            ],
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls
        ]);
    }

   /**
 * @OA\Get(
 *     path="/api/reports/patients/{id}/call-history/pdf",
 *     summary="Obtener historial de llamadas de un paciente en formato PDF",
 *     description="Este endpoint permite obtener el historial de llamadas (entrantes y salientes) de un paciente específico en formato PDF.",
 *     tags={"Reportes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del paciente",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="PDF generado con éxito",
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/pdf",
 *                 @OA\Schema(type="string", format="binary")
 *             )
 *         }
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Paciente no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Paciente no encontrado")
 *         )
 *     ),
 *     security={{"bearerAuth": {}}},
 * )
 */
    public function getPatientCallHistoryPDF($patientId)
    {
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        $incomingCalls = \App\Models\IncomingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        $outgoingCalls = \App\Models\OutgoingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.patientCallHistory', [
            'patient' => $patient,
            'incomingCalls' => $incomingCalls,
            'outgoingCalls' => $outgoingCalls
        ]);

        return $pdf->download('Historial_Llamadas_' . $patient->name . '_' . $patient->last_name . '.pdf');
    }

    /**
 * @OA\Get(
 *     path="/api/reports/emergencies",
 *     summary="Obtener emergencias filtradas por tipo y zona",
 *     description="Este endpoint permite obtener las emergencias de tipo social, médica, crisis de soledad y alarmas no respondidas, con la opción de filtrar por zona.",
 *     tags={"Reportes"},
 *     @OA\Parameter(
 *         name="zone",
 *         in="query",
 *         description="ID de zona para filtrar las emergencias. Puede ser un solo ID o una lista de zonas.",
 *         required=false,
 *         @OA\Schema(type="array", @OA\Items(type="integer"))
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Emergencias obtenidas exitosamente",
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/json",
 *                 @OA\Schema(
 *                     type="object",
 *                     @OA\Property(property="zones", type="array", @OA\Items(type="integer")),
 *                     @OA\Property(
 *                         property="emergencies",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer"),
 *                             @OA\Property(property="type", type="string"),
 *                             @OA\Property(property="timestamp", type="string", format="date-time"),
 *                             @OA\Property(property="patient_id", type="integer"),
 *                             @OA\Property(property="patient", type="object", ref="#/components/schemas/Patient")
 *                         )
 *                     )
 *                 )
 *             )
 *         }
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Petición inválida, formato de zona incorrecto",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Zona no válida")
 *         )
 *     )
 * )
 */
    public function getEmergencies(Request $request)
    {
        $emergencyTypes = ['social_emergency', 'medical_emergency', 'loneliness_crisis', 'unanswered_alarm'];
        $zoneIds = $request->input('zone') ?? $request->input('zones', []);

        if (!is_array($zoneIds)) {
            $zoneIds = [$zoneIds];
        }

        $query = \App\Models\IncomingCall::whereIn('type', $emergencyTypes)
            ->with('patient')
            ->orderByDesc('timestamp');

        if (!empty($zoneIds)) {
            $query->whereHas('patient', function ($q) use ($zoneIds) {
                $q->whereIn('zone_id', $zoneIds);
            });
        }
        $emergencies = $query->get();
        foreach ($emergencies as $emergency) {
            if ($emergency->patient) {
                logger("Emergency ID {$emergency->id} - Patient ID {$emergency->patient_id} - Zone ID: {$emergency->patient->zone_id}");
            } else {
                logger("Emergency ID {$emergency->id} - Patient NOT FOUND");
            }
        }

        return response()->json([
            'zones' => $zoneIds,
            'emergencies' => $emergencies
        ]);
    }


    /**
 * @OA\Get(
 *     path="/api/reports/emergencies/pdf",
 *     summary="Generar reporte en PDF de las emergencias filtradas por tipo y zona",
 *     description="Este endpoint permite generar un reporte en formato PDF de las emergencias de tipo social, médica, crisis de soledad y alarmas no respondidas, con la opción de filtrar por zona.",
 *     tags={"Reportes"},
 *     @OA\Parameter(
 *         name="zone",
 *         in="query",
 *         description="ID de zona para filtrar las emergencias. Puede ser un solo ID o una lista de zonas.",
 *         required=false,
 *         @OA\Schema(type="array", @OA\Items(type="integer"))
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reporte PDF de emergencias generado exitosamente",
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/pdf",
 *                 @OA\Schema(type="string", format="binary")
 *             )
 *         }
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Petición inválida, formato de zona incorrecto",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Zona no válida")
 *         )
 *     )
 * )
 */
    public function getEmergenciesPDF(Request $request)
    {
        $emergencyTypes = ['social_emergency', 'medical_emergency', 'loneliness_crisis', 'unanswered_alarm'];
        $zoneIds = $request->input('zone') ?? $request->input('zones', []);

        if (!is_array($zoneIds)) {
            $zoneIds = [$zoneIds];
        }
        $query = \App\Models\IncomingCall::whereIn('type', $emergencyTypes)
            ->with('patient')
            ->orderByDesc('timestamp'); 

        if (!empty($zoneIds)) {
            $query->whereHas('patient', function ($q) use ($zoneIds) {
                $q->whereIn('zone_id', $zoneIds); 
            });
        }
        $emergencies = $query->get();
        $zones = [];
        foreach ($emergencies as $emergency) {
            $zoneId = $emergency->patient ? $emergency->patient->zone_id : null;
            if ($zoneId) {
                if (!isset($zones[$zoneId])) {
                    $zones[$zoneId] = [
                        'zone_id' => $zoneId,
                        'emergencies' => [],
                    ];
                }
                $zones[$zoneId]['emergencies'][] = [
                    'id' => $emergency->id,
                    'description' => $emergency->description,
                    'patient_name' => $emergency->patient->name . ' ' . $emergency->patient->last_name,
                    'type' => $emergency->type,
                    'timestamp' => $emergency->timestamp,
                ];
            }
        }
        $pdf = PDF::loadView('pdf.emergencies', compact('zones'));
        return $pdf->download('listado_emergencias.pdf');
    }
}
