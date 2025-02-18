<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutGoingCallRequest;
use Illuminate\Http\Request;
use App\Models\OutgoingCall;

class OutgoingCallController extends Controller
{
    
    public function index()
    {
    return response()->json(OutgoingCall::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OutgoingCallRequest $request)
    {
        $outgoingCall = OutgoingCall::create($request->validated());
        return response()->json($outgoingCall, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OutgoingCallRequest $request, OutgoingCall $outgoingCall)
{
    $outgoingCall->update($request->validated());

    return response()->json([
        'message' => 'Outgoing call updated successfully',
        'data' => $outgoingCall
    ], 200);
}

    public function destroy(OutgoingCall $outgoingCall)
    {
        $outgoingCall->delete();
        return response()->json(['message' => 'Outgoing call deleted successfully']);
    }
}
