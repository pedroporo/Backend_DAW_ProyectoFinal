<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;


class PatientController extends BaseController
{
   /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="Llista tots els pacients amb paginació",
     *     tags={"Pacientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/PatientResource")
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://localhost/api/patients?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://localhost/api/patients?page=3"),
     *                 @OA\Property(property="prev", type="string", example="null"),
     *                 @OA\Property(property="next", type="string", example="http://localhost/api/patients?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="path", type="string", example="http://localhost/api/patients"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=45)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return PatientResource::collection(Patient::paginate());
    }

/**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Crea un nuevo paciente",
     *     tags={"Pacientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Paciente creado con exito",
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     )
     * )
     */
    public function store(PatientRequest $request)
    {
        $patient = Patient::create($request->validated());
        return $this->sendResponse($patient, __('messages.patient.create', ['name' => $patient->name]),201);
    }
/**
     * @OA\Get(
     *     path="/api/patients/{id}",
     *     summary="Muestra un paciente",
     *     tags={"Pacientes"},
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
     *         description="El paciente :name ha sido recumerado",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     )
     * )
     */

    public function show(Patient $patient)
    {
        return $this->sendResponse(new PatientResource($patient), __('messages.patient.get', ['name' => $patient->name]), 201);
    }
/**
     * @OA\Put(
     *     path="/api/patients/{id}",
     *     summary="Actualiza un paciente",
     *     tags={"Pacientes"},
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
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El paciente :name se ha actualizado con exito.",
     *         @OA\JsonContent(ref="#/components/schemas/PatientRequest")
     *     )
     * )
     */


    public function update(PatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());
        return $this->sendResponse($patient, __('messages.patient.update', ['name' => $patient->name]), 201);
    }
/**
     * @OA\Delete(
     *     path="/api/patients/{id}",
     *     summary="Elimina un paciente",
     *     tags={"Pacientes"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del paciente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El paciente ha sido eliminado."
     *     )
     * )
     */

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return $this->sendResponse(null, __('messages.patient.delete'), 201);
    }
}
