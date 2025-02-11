<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
     /**
     * @OA\Get(
     *     path="/api/contacts",
     *     summary="Llista tots els pacients amb paginació",
     *     tags={"Contactos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contactos",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/ContactResource")
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://localhost/api/contacts?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://localhost/api/contacts?page=3"),
     *                 @OA\Property(property="prev", type="string", example="null"),
     *                 @OA\Property(property="next", type="string", example="http://localhost/api/contacts?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="path", type="string", example="http://localhost/api/contacts"),
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
        return ContactResource::collection(Contact::paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/contacts",
     *     summary="Crea un nuevo contacto",
     *     tags={"Contactos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ContactRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contacto creado con exito",
     *         @OA\JsonContent(ref="#/components/schemas/ContactRequest")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function store(ContactRequest $request,int $patient_id)
    {
        $request->request->set('patient_id',$patient_id);
        $contact = Contact::create($request->validated());
        return $this->sendResponse($contact, __('messages.contact.create', ['first_name' => $contact->first_name]), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contacts/{id}",
     *     summary="Muestra un contacto",
     *     tags={"Contactos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del contacto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El contacto :first_name ha sido recumerado",
     *         @OA\JsonContent(ref="#/components/schemas/ContactResource")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function show(Contact $contact)
    {
        return $this->sendResponse(new ContactResource($contact), __('messages.contact.get', ['first_name' => $contact->first_name]), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/contacts/{id}",
     *     summary="Actualiza un contacto",
     *     tags={"Contactos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del contacto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ContactRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El contacto :name se ha actualizado con exito.",
     *         @OA\JsonContent(ref="#/components/schemas/ContactRequest")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());
        return $this->sendResponse($contact, __('messages.contact.update', ['first_name' => $contact->first_name]), 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/contacts/{id}",
     *     summary="Elimina un contacto",
     *     tags={"Contactos"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del contacto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El contacto ha sido eliminado."
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return $this->sendResponse(null, __('messages.contact.delete'), 201);
    }
    /**
     * @OA\Get(
     *     path="/api/patients/{id}/contacts",
     *     summary="Muestra todos los contactos de un paciente",
     *     tags={"Contactos"},
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
     *         description="Todos los contactos relacionados con ese paciente han sido recuperados.",
     *         @OA\JsonContent(ref="#/components/schemas/ContactResource")
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="No has iniciado sesion."
     *     )
     * )
     */
    public function getContactsByPatiente(int $patient_id)
    {
        return $this->sendResponse(ContactResource::collection(Contact::where('patient_id', $patient_id)->get()), __('messages.contact.getContByPa'), 201);
    }
}
