<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomingCallRequest;
use App\Models\IncomingCall;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Incoming Calls",
 *     description="Gestión de llamadas entrantes"
 * )
 */
class IncomingCallController extends CallController
{

    /**
     * @OA\Get(
     *     path="/api/incoming-calls",
     *     summary="Listar todas las llamadas entrantes",
     *     tags={"Incoming Calls"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de llamadas entrantes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/IncomingCall"))
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
        return response()->json(IncomingCall::all());
    }


    /**
     * @OA\Post(
     *     path="/api/incoming-calls",
     *     summary="Crear una nueva llamada entrante",
     *     tags={"Incoming Calls"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IncomingCall")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Llamada entrante creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/IncomingCall")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function store(IncomingCallRequest $request)
    {
        $incomingCall = IncomingCall::create($request->validated());
        return response()->json($incomingCall, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/incoming-calls/{id}",
     *     summary="Obtener una llamada entrante por su ID",
     *     tags={"Incoming Calls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la llamada entrante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamada entrante obtenida correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/IncomingCall")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Llamada no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Incoming call not found")
     *         )
     *     )
     * )
     */
    public function show(IncomingCall $incomingCall)
    {
        return response()->json($incomingCall);
    }

    /**
     * @OA\Put(
     *     path="/api/incoming-calls/{id}",
     *     summary="Actualizar una llamada entrante",
     *     tags={"Incoming Calls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la llamada entrante a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IncomingCall")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamada entrante actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/IncomingCall")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Llamada entrante no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Incoming call not found"))
     *     )
     * )
     */
    public function update(IncomingCallRequest $request, IncomingCall $incomingCall)
    {

        $incomingCall->update($request->all());
        return response()->json($incomingCall);
    }

    /**
     * @OA\Delete(
     *     path="/api/incoming-calls/{id}",
     *     summary="Eliminar una llamada entrante",
     *     tags={"Incoming Calls"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la llamada entrante a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Llamada entrante eliminada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Incoming call deleted successfully"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Llamada entrante no encontrada",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Incoming call not found"))
     *     )
     * )
     */
    public function destroy(IncomingCall $incomingCall)
    {
        $incomingCall->delete();
        return response()->json(['message' => 'Incoming call deleted successfully']);
    }
}
