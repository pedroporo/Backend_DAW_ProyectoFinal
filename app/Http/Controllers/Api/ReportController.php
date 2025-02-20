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

    public function getDoneCallsByDate(Request $request)
    {
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

    public function getDoneCallsByDatePDF(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        $outgoingCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp', 'desc')
            ->get();

        $pdf = PDF::loadView('pdf.callsDoneDate', [
            'operatorName' => $user->name,
            'date' => $date,
            'outgoingCalls' => $outgoingCalls,
        ]);

        return $pdf->download("llamadas_realizadas_{$date}.pdf");
    }

    public function getScheduledCallsPDF(Request $request)
    {
        // Verificar si el usuario está autenticado (o usar un usuario específico para pruebas)
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Filtrar las llamadas salientes planificadas
        $scheduledCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->where('is_planned', 1) // Solo llamadas planificadas
            ->orderBy('timestamp', 'asc')
            ->get();

        // Cargar la vista que deseas convertir a PDF
        $pdf = PDF::loadView('pdf.callsScheduled', ['user' => $user, 'scheduledCalls' => $scheduledCalls]);

        // Descargar el PDF generado
        return $pdf->download('llamadas_planificadas.pdf');
    }

    public function getScheduledCallsDate(Request $request)
    {
        // Obtener la fecha desde la URL o usar la fecha actual si no se proporciona    
        $date = $request->input('date', now()->toDateString());

        // Verificar si el usuario está autenticado (o usar un usuario específico para pruebas)
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Filtrar las llamadas salientes planificadas por la fecha proporcionada
        $scheduledCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->where('is_planned', 1) // Solo llamadas planificadas
            ->whereDate('timestamp', $date) // Filtrar por fecha específica
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json([
            'date' => $date,
            'scheduled_calls' => $scheduledCalls
        ]);
    }

    public function getScheduledCallsDatePDF(Request $request)
    {
        // Obtener la fecha desde la URL o usar la fecha actual si no se proporciona    
        $date = $request->input('date', now()->toDateString());

        // Verificar si el usuario está autenticado (o usar un usuario específico para pruebas)
        $user = auth()->user() ?? User::find(2);

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Filtrar las llamadas salientes planificadas por la fecha proporcionada
        $scheduledCalls = \App\Models\OutgoingCall::with('patient', 'alert')
            ->where('user_id', $user->id)
            ->where('is_planned', 1) // Solo llamadas planificadas
            ->whereDate('timestamp', $date) // Filtrar por fecha específica
            ->orderBy('timestamp', 'asc')
            ->get();

        // Cargar la vista con las llamadas planificadas
        $pdf = PDF::loadView('pdf.callsScheduledDate', [
            'scheduledCalls' => $scheduledCalls,
            'date' => $date,
        ]);

        // Retornar el PDF para su descarga
        return $pdf->download('scheduled_calls_' . $date . '.pdf');
    }


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

    //Podem filtrar per tipus per a no repetir codig
    public function getPatientCallHistory($patientId)
    {
        // Buscar el paciente
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        // Obtener llamadas entrantes y salientes del paciente
        $incomingCalls = \App\Models\IncomingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        $outgoingCalls = \App\Models\OutgoingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'last_name' => $patient->last_name
            ],
            'incoming_calls' => $incomingCalls,
            'outgoing_calls' => $outgoingCalls
        ]);
    }

    public function getPatientCallHistoryPDF($patientId)
    {
        // Buscar el paciente
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        // Obtener llamadas entrantes y salientes del paciente
        $incomingCalls = \App\Models\IncomingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        $outgoingCalls = \App\Models\OutgoingCall::where('patient_id', $patientId)
            ->orderBy('timestamp', 'desc')
            ->get();

        // Generar el PDF con la vista 'patientCallHistory'
        $pdf = Pdf::loadView('pdf.patientCallHistory', [
            'patient' => $patient,
            'incomingCalls' => $incomingCalls,
            'outgoingCalls' => $outgoingCalls
        ]);

        // Descargar el PDF con el nombre del paciente
        return $pdf->download('Historial_Llamadas_' . $patient->name . '_' . $patient->last_name . '.pdf');
    }

    public function getCallHistoryByPatientAndType($patientId, Request $request) {}
    public function getCallHistoryByPatientAndTypePDF($patientId, Request $request) {}

     //Listar emergencias asignades al teleoperador
    // Son les cridades entrants de tipus Atencio d'emergencies
    public function getEmergencies() {}

    public function getEmergencyActionsByZone($zoneId) {}

}
