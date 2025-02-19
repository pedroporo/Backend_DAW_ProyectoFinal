<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llamadas Realizadas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: gray;
        }
    </style>
</head>
<body>
    <h2>Llamadas Realizadas por {{ $operatorName }}</h2>

    <!-- Llamadas Entrantes -->
    <h3>Llamadas Entrantes</h3>
    @if($incomingCalls->isEmpty())
        <p class="no-data">No hay llamadas entrantes registradas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha y Hora</th>
                    <th>Tipo</th> <!-- Se agrega el tipo de llamada -->
                    <th>Descripción</th>
                    <th>Paciente</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incomingCalls as $call)
                    @php
                        // Nombre completo del paciente
                        $patientFullName = $call->patient ? $call->patient->name . ' ' . $call->patient->last_name : 'Desconocido';
                    @endphp
                    <tr>
                        <td>{{ $call->id }}</td>
                        <td>{{ $call->timestamp }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $call->type)) }}</td> <!-- Mostrar tipo con formato -->
                        <td>{{ $patientFullName }}</td>
                        <td>{{ $call->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Llamadas Salientes -->
    <h3>Llamadas Salientes</h3>
    @if($outgoingCalls->isEmpty())
        <p class="no-data">No hay llamadas salientes registradas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha y Hora</th>
                    <th>Descripción</th>
                    <th>Paciente</th>
                    <th>¿Planeada?</th>
                    <th>¿Tiene Alarma?</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outgoingCalls as $call)
                    @php
                        // Nombre completo del paciente
                        $patientFullName = $call->patient ? $call->patient->name . ' ' . $call->patient->last_name : 'Desconocido';

                        // Determinar si la llamada es planeada
                        $isPlanned = $call->is_planned ? 'Sí' : 'No';

                        // Determinar si tiene una alarma asociada y su tipo
                        $hasAlarm = $call->alarm ? 'Sí (ID: ' . $call->alarm->id . ', Tipo: ' . $call->alarm->type . ')' : 'No';
                    @endphp
                    <tr>
                        <td>{{ $call->id }}</td>
                        <td>{{ $call->timestamp }}</td>
                        <td>{{ $call->description }}</td>
                        <td>{{ $patientFullName }}</td>
                        <td>{{ $isPlanned }}</td>
                        <td>{{ $hasAlarm }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
