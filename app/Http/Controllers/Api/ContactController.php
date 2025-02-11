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
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactResource::collection(Contact::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request,int $patient_id)
    {
        $request->request->set('patient_id',$patient_id);
        $contact = Contact::create($request->validated());
        return $this->sendResponse($contact, __('messages.contact.create', ['first_name' => $contact->first_name]), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return $this->sendResponse(new ContactResource($contact), __('messages.contact.get', ['first_name' => $contact->first_name]), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());
        return $this->sendResponse($contact, __('messages.contact.update', ['first_name' => $contact->first_name]), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }
    /**
     * @OA\Get(
     *     path="/api/patients/{id}/contacts",
     *     summary="Muestra todos los contactos de un paciente",
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
     *         description="Todos los pacientes de la zona han sido recuperados",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
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
