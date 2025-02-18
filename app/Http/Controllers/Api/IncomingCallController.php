<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomingCallRequest;
use App\Models\IncomingCall;
use Illuminate\Http\Request;

class IncomingCallController extends CallController
{
    public function index()
{
    return response()->json(IncomingCall::all());
}

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomingCallRequest $request)
    {
        $incomingCall = IncomingCall::create($request->validated());
        return response()->json($incomingCall, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(IncomingCallRequest $request, IncomingCall $incomingCall)
    {
 
        $incomingCall->update($request->all());
        return response()->json($incomingCall);
    }

    public function destroy(IncomingCall $incomingCall)
    {
        $incomingCall->delete();
        return response()->json(['message' => 'Incoming call deleted successfully']);
    }

}
