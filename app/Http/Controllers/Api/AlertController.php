<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AlertRequest;
use App\Http\Resources\AlertResource;
use App\Models\Alert;

class AlertController extends BaseController
{
   /**
     * @OA\Get(
     *     path="/api/alerts",
     *     summary="Llista tots els alertes amb paginació",
     *     tags={"Alertas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de alerts",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/AlertResource")
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://localhost/api/alerts?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://localhost/api/alerts?page=3"),
     *                 @OA\Property(property="prev", type="string", example="null"),
     *                 @OA\Property(property="next", type="string", example="http://localhost/api/alerts?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="path", type="string", example="http://localhost/api/alerts"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=45)
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */

    public function index()
    {
        return AlertResource::collection(Alert::paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/alerts",
     *     summary="Crea una nueva alerta",
     *     tags={"Alertas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AlertRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Alerta creada con exito",
     *         @OA\JsonContent(ref="#/components/schemas/AlertRequest")
     *     )
     * )
     */
    public function store(AlertRequest $request)
    {
        $alert = Alert::create($request->validated());
        return $this->sendResponse($alert, __('messages.alert.create'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/alerts/{id}",
     *     summary="Muestra una alerta",
     *     tags={"Alertas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la alerta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La alerta ha sido recuperada",
     *         @OA\JsonContent(ref="#/components/schemas/AlertResource")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */

    public function show(Alert $alert)
    {
        return $this->sendResponse(new AlertResource($alert), __('messages.alert.get'), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/alerts/{id}",
     *     summary="Actualiza un alerta",
     *     tags={"Alertas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la alerta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AlertRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La alerta se ha actualizado con exito.",
     *         @OA\JsonContent(ref="#/components/schemas/AlertRequest")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function update(AlertRequest $request, Alert $alert)
    {
        $alert->update($request->validated());
        return $this->sendResponse($alert, __('messages.alert.update'), 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/alerts/{id}",
     *     summary="Elimina un alerta",
     *     tags={"Alertas"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del alerta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La alerta ha sido eliminada."
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function destroy(Alert $alert)
    {
        $alert->delete();
        return $this->sendResponse(null, __('messages.alert.delete'), 201);
    }
}
