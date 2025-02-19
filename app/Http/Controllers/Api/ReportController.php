<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Zone;
use App\Models\Patient;
use App\Models\User;
use App\Enums\Alarms_type;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    //Listar emergencias asignades al teleoperador
    // Son les cridades entrants de tipus Atencio d'emergencies
    public function getEmergencies() {}


    public function getEmergencyActionsByZone($zoneId) {}


    public function getPatients()
    {
        //Recoger los pacientes que tenga asignado
        // el teleoperador y ordenarlos por apellido

        //$user = auth()->user(); esto es solo de prueba

        $user = \App\Models\User::find(2);
        $zonesIds = $user->zones()->pluck('zones.id'); // Obtiene los IDs de las zonas
        $patients = \App\Models\Patient::whereIn('zone_id', $zonesIds)->orderBy('last_name', 'asc')->get();

        return response()->json($patients);
    }

    public function getPatientsPDF()
    {
        // Obtener las zonas asociadas al usuario con ID 9
        $user = User::find(2);
        $zonesIds = $user->zones()->pluck('zones.id'); // Obtiene los IDs de las zonas asociadas al usuario

        // Obtener los pacientes que estén en las zonas asociadas al usuario
        $patients = Patient::whereIn('zone_id', $zonesIds)->orderBy('last_name', 'asc')->get();

        // Pasar el nombre del teleoperador a la vista
        $operatorName = $user->name; // O el campo que almacene el nombre del teleoperador

        // Cargar la vista y generar el PDF
        $pdf = Pdf::loadView('pdf.patients', ['patients' => $patients, 'operatorName' => $operatorName]);

        // Devolver el PDF como descarga
        return $pdf->download('patients_list.pdf');
    }

    public function getDoneCallsByDate(Request $request) {
        // Obtener la fecha desde la URL o usar la fecha actual si no se proporciona
        $date = $request->input('date', now()->toDateString());
    
        // Verificar si el usuario está autenticado (o usar un usuario específico para pruebas)
        $user = auth()->user() ?? User::find(2);
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    
        // Filtrar las llamadas salientes que coincidan con la fecha seleccionada
        $outgoingCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->whereDate('timestamp', $date) // Filtrar por fecha específica
            ->orderBy('timestamp', 'desc')
            ->get();
    
        return response()->json([
            'date' => $date,
            'outgoing_calls' => $outgoingCalls
        ]);
    }

    public function getDoneCallsByDatePDF(Request $request) {

    }

    public function getScheduledCalls(Request $request) {}

    public function getScheduledCallsPDF(Request $request) {}


    public function getCallsUser()
    {

        // Verificar si el usuario está autenticado
        //$user = auth()->user();
        $user = User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $now = now();

        // Obtener llamadas entrantes realizadas por el usuario y que ya ocurrieron
        $incomingCalls = \App\Models\IncomingCall::where('user_id', $user->id)
            ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
            ->orderBy('timestamp', 'desc')
            ->get();

        // Obtener llamadas salientes realizadas por el usuario y que ya ocurrieron
        $outgoingCalls = \App\Models\OutgoingCall::where('user_id', $user->id)
            ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
            ->orderBy('timestamp', 'desc')
            ->get();

        // Responder con las llamadas separadas en la respuesta JSON
        return response()->json([
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls,
        ]);
    }

    public function getUserCallsPDF()
{
    // Verificar si el usuario está autenticado
    //$user = auth()->user();
    $user = User::find(2);

    if (!$user) {
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }

    // Fecha actual para filtrar solo las llamadas ya realizadas
    $now = now();

    // Obtener llamadas entrantes realizadas por el usuario y que ya ocurrieron
    $incomingCalls = \App\Models\IncomingCall::where('user_id', $user->id)
        ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
        ->orderBy('timestamp', 'desc')
        ->get();

    // Obtener llamadas salientes realizadas por el usuario y que ya ocurrieron
    $outgoingCalls = \App\Models\OutgoingCall::where('user_id', $user->id)
        ->where('timestamp', '<=', $now) // Solo llamadas ya realizadas
        ->orderBy('timestamp', 'desc')
        ->get();

    // Cargar la vista que deseas convertir a PDF
    $pdf = PDF::loadView('pdf.callsDone', ['operatorName' => $user->name], [
        'incomingCalls' => $incomingCalls,
        'outgoingCalls' => $outgoingCalls,
    ]);

    // Descargar el PDF
    return $pdf->download('llamadas_realizadas.pdf');
}


    public function getPatientCallHistory($patientId) {}
    public function getPatientCallHistoryPDF($patientId) {}

    public function getCallHistoryByPatientAndType($patientId, Request $request) {}
    public function getCallHistoryByPatientAndTypePDF($patientId, Request $request) {}
}
