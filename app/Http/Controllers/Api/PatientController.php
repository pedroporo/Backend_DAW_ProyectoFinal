<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends BaseController
{
   
    public function index()
    {
        return PatientResource::collection(Patient::paginate());
    }


    public function store(PatientRequest $request)
    {
        $patient = Patient::create($request->validated());
        return $this->sendResponse($patient, __('messages.patient.create', ['name' => $patient->name]),201);
    }


    public function show(Patient $patient)
    {
        return $this->sendResponse(new PatientResource($patient), __('messages.patient.get', ['name' => $patient->name]), 201);
    }


    public function update(PatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());
        return $this->sendResponse($patient, __('messages.patient.update', ['name' => $patient->name]), 201);
    }


    public function destroy(Patient $patient)
    {
        $patient->delete();
        return $this->sendResponse(null, __('messages.patient.delete'), 201);
    }
}
