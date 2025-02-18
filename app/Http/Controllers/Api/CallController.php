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
 */
class CallController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/calls",
     *     summary="Obtener todas las llamadas entrantes y salientes",
     *     tags={"Calls"},
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

}
