<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ZoneRequest;
use App\Http\Resources\ZoneResource;
use App\Models\Zone;

class ZoneController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/zones",
     *     summary="Llista tots les zones amb paginació",
     *     tags={"Zonas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de zones",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/ZoneResource")
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://localhost/api/zones?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://localhost/api/zones?page=3"),
     *                 @OA\Property(property="prev", type="string", example="null"),
     *                 @OA\Property(property="next", type="string", example="http://localhost/api/zones?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="path", type="string", example="http://localhost/api/zones"),
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
        return ZoneResource::collection(Zone::paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/zones",
     *     summary="Crea una nueva zona",
     *     tags={"Zonas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ZoneRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Zona creado con exito",
     *         @OA\JsonContent(ref="#/components/schemas/ZoneRequest")
     *     )
     * )
     */
    public function store(ZoneRequest $request)
    {
        $zone = Zone::create($request->validated());
        return $this->sendResponse($zone, __('messages.zone.create', ['name' => $zone->name]), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/zones/{id}",
     *     summary="Muestra una zona",
     *     tags={"Zonas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la zona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La zona :name ha sido recuperada",
     *         @OA\JsonContent(ref="#/components/schemas/ZoneResource")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function show(Zone $zone)
    {
        return $this->sendResponse(new ZoneResource($zone), __('messages.zone.get', ['name' => $zone->name]), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/zones/{id}",
     *     summary="Actualiza un zona",
     *     tags={"Zonas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del paciente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ZoneRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La zona :name se ha actualizado con exito.",
     *         @OA\JsonContent(ref="#/components/schemas/ZoneRequest")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function update(ZoneRequest $request, Zone $zone)
    {
        $zone->update($request->validated());
        return $this->sendResponse($zone, __('messages.zone.update', ['name' => $zone->name]), 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/zones/{id}",
     *     summary="Elimina una zona",
     *     tags={"Zonas"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del paciente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El paciente ha sido eliminado."
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();
        return $this->sendResponse(null, __('messages.zone.delete'), 201);
    }
}
