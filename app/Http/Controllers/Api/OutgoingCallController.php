<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutGoingCallRequest;
use Illuminate\Http\Request;
use App\Models\OutgoingCall;

/**
 * @OA\Tag(
 *     name="Outgoing Calls",
 *     description="Gestión de llamadas salientes"
 * )
 */
class OutgoingCallController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/outgoing-calls",
     *     summary="Listar todas las llamadas salientes",
     *     tags={"Outgoing Calls"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de llamadas salientes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/OutgoingCall"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(OutgoingCall::all());
    }


    /**
     * @OA\Get(
     *     path="/api/outgoing-calls/{id}",
     *     summary="Obtener una llamada saliente por ID",
     *     tags={"Outgoing Calls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la llamada saliente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamada saliente encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/OutgoingCall")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Llamada saliente no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Outgoing call not found"))
     *     )
     * )
     */
    public function show(OutgoingCall $outgoingCall)
    {
        return response()->json($outgoingCall);
    }

    /**
     * @OA\Post(
     *     path="/api/outgoing-calls",
     *     summary="Crear una nueva llamada saliente",
     *     tags={"Outgoing Calls"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OutgoingCall")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Llamada saliente creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/OutgoingCall")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function store(OutgoingCallRequest $request)
    {
        $outgoingCall = OutgoingCall::create($request->validated());
        return response()->json($outgoingCall, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/outgoing-calls/{id}",
     *     summary="Actualizar una llamada saliente",
     *     tags={"Outgoing Calls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la llamada saliente a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OutgoingCall")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamada saliente actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/OutgoingCall")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Llamada saliente no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Outgoing call not found"))
     *     )
     * )
     */
    public function update(OutgoingCallRequest $request, OutgoingCall $outgoingCall)
    {
        $outgoingCall->update($request->validated());

        return response()->json([
            'message' => 'Outgoing call updated successfully',
            'data' => $outgoingCall
        ], 200);
    }


    /**
 * @OA\Delete(
 *     path="/api/outgoing-calls/{id}",
 *     summary="Eliminar una llamada saliente",
 *     tags={"Outgoing Calls"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de la llamada saliente a eliminar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Llamada saliente eliminada",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Outgoing call deleted successfully"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Llamada saliente no encontrada",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Outgoing call not found"))
 *     )
 * )
 */
    public function destroy(OutgoingCall $outgoingCall)
    {
        $outgoingCall->delete();
        return response()->json(['message' => 'Outgoing call deleted successfully']);
    }
}
