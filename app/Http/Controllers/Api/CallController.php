<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Call;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\IncomingCall;
use App\Models\OutgoingCall;
use App\Models\Patient;


/**
 * @OA\Tag(
 *     name="Calls",
 *     description="Operaciones sobre las llamadas entrantes y salientes"
 * )
 * 
 */
class CallController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/calls",
     *     summary="Obtener todas las llamadas entrantes y salientes",
     *     tags={"Calls"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Llamadas obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="incoming_calls", type="array", @OA\Items(ref="#/components/schemas/IncomingCall")),
     *             @OA\Property(property="outgoing_calls", type="array", @OA\Items(ref="#/components/schemas/OutgoingCall"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        
        $incomingCalls = IncomingCall::all();
        $outgoingCalls = OutgoingCall::all();
    
        return response()->json([
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls
        ]);    }


    /**
     * @OA\Get(
     *     path="/api/patients/{id}/calls",
     *     summary="Obtener todas las llamadas de un paciente (entrantes y salientes)",
     *     tags={"Calls"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del paciente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamadas del paciente obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="incoming_calls", type="array", @OA\Items(ref="#/components/schemas/IncomingCall")),
     *             @OA\Property(property="outgoing_calls", type="array", @OA\Items(ref="#/components/schemas/OutgoingCall"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paciente no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Patient not found")
     *         )
     *     )
     * )
     */
    public function getCallsForPatient($id){

        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $incomingCalls = IncomingCall::where('patient_id', $id)->get();
        $outgoingCalls = OutgoingCall::where('patient_id', $id)->get();
        return response()->json([
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls
        ]);
    }



    /**
     * @OA\Get(
     *     path="/api/filter-calls",
     *     summary="Filtrar llamadas entrantes y salientes por fecha, zona y tipo",
     *     tags={"Calls"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         description="Fecha para filtrar las llamadas (formato: YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date", example="2025-02-20")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="Tipo de llamada a filtrar: 'incoming' o 'outgoing'",
     *         @OA\Schema(type="string", example="incoming")
     *     ),
     *     @OA\Parameter(
     *         name="zone",
     *         in="query",
     *         required=false,
     *         description="Zona para filtrar las llamadas",
     *         @OA\Schema(type="string", example="zone1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamadas filtradas correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="incoming_calls", type="array", @OA\Items(ref="#/components/schemas/IncomingCall")),
     *             @OA\Property(property="outgoing_calls", type="array", @OA\Items(ref="#/components/schemas/OutgoingCall"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Parámetros no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function getFilterCalls(Request $request)
{
    $date = $request->query('date');
    $type = $request->query('type');
    $zone = $request->query('zone'); // Este es el patient_id

    if ($type && strtolower($type) === 'incoming') {
        $query = IncomingCall::query();

        if ($zone) {
            // Obtener el patient_id, y luego buscar el zone_id
            $patient = Patient::find($zone); // El parámetro 'zone' es en realidad el patient_id
            if ($patient) {
                $zoneId = $patient->zone_id; // Obtener el zone_id del paciente
                $query->whereHas('patient', function($q) use ($zoneId) {
                    $q->where('zone_id', $zoneId); // Filtrar por zone_id del paciente
                });
            }
        }

        if ($date) {
            // Filtramos por la fecha usando el campo 'timestamp'
            $query->whereDate('timestamp', $date);
        }

        $incomingCalls = $query->get();

        return response()->json([
            'incoming_calls' => $incomingCalls
        ]);
    } elseif ($type && strtolower($type) === 'outgoing') {
        $query = OutgoingCall::query();

        if ($zone) {
            // Obtener el patient_id, y luego buscar el zone_id
            $patient = Patient::find($zone); // El parámetro 'zone' es en realidad el patient_id
            if ($patient) {
                $zoneId = $patient->zone_id; // Obtener el zone_id del paciente
                $query->whereHas('patient', function($q) use ($zoneId) {
                    $q->where('zone_id', $zoneId); // Filtrar por zone_id del paciente
                });
            }
        }

        if ($date) {
            // Filtramos por la fecha usando el campo 'timestamp'
            $query->whereDate('timestamp', $date);
        }

        $outgoingCalls = $query->get();

        return response()->json([
            'outgoing_calls' => $outgoingCalls
        ]);
    } else {
        // Si no se especifica el tipo, devolvemos ambas, aplicando los filtros de fecha y zona
        $incomingQuery = IncomingCall::query();
        $outgoingQuery = OutgoingCall::query();

        if ($zone) {
            // Obtener el patient_id, y luego buscar el zone_id
            $patient = Patient::find($zone); // El parámetro 'zone' es en realidad el patient_id
            if ($patient) {
                $zoneId = $patient->zone_id; // Obtener el zone_id del paciente
                $incomingQuery->whereHas('patient', function($q) use ($zoneId) {
                    $q->where('zone_id', $zoneId); // Filtrar por zone_id del paciente
                });
                $outgoingQuery->whereHas('patient', function($q) use ($zoneId) {
                    $q->where('zone_id', $zoneId); // Filtrar por zone_id del paciente
                });
            }
        }

        if ($date) {
            // Filtramos por la fecha usando el campo 'timestamp'
            $incomingQuery->whereDate('timestamp', $date);
            $outgoingQuery->whereDate('timestamp', $date);
        }

        $incomingCalls = $incomingQuery->get();
        $outgoingCalls = $outgoingQuery->get();

        return response()->json([
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls
        ]);
    }
}

}




