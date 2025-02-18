<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Call;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\IncomingCall;
use App\Models\OutgoingCall;
use App\Models\Patient;

class CallController extends BaseController
{
    /**
     * Display a listing of the resource.
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
     * Display the specified resource.
     */
    public function show(Call $call)
    {
        return response()->json($call);
    }

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
