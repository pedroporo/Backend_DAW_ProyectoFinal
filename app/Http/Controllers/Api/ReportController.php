<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }


    //Listar emergencias
    public function getEmergencies(){

        $user = Auth::user();

        if (!$user->zone_id){
            return response()->json(['Error' => 'No tienes una zona asignada'], 401);

        }

        //Llamadas entrantes consideradas emergencia.
        $emergencyTypes = ['social_emergency', 'medical_emergency', 'loneliness_crisis', 'unanswered_alarm'];

        // Obtener llamadas solo de los pacientes en la zona del operador
        $emergencies = Call::whereIn('incoming_calls_type_enum', $emergencyTypes)
            ->whereHas('patient', function ($query) use ($user) {
                $query->where('zone_id', $user->zone_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            return response()->json($emergencies);
    }
}
