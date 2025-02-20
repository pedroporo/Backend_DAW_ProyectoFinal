<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llamadas Planificadas para el {{ $date }}</title>
    <style>
         body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Llamadas Planificadas - {{ $date }}</h2>
    
    @if($scheduledCalls->isEmpty())
        <p>No hay llamadas planificadas para esta fecha.</p>
    @else
        <!-- Mostrar el nombre del teleoperador en la parte superior -->
        <p><strong>Teleoperador:</strong> {{ $scheduledCalls->first()->user->name ?? 'No asignado' }}</p>

        <table>
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Descripción</th>
                    <th>Hora de la Llamada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scheduledCalls as $call)
                    <tr>
                        <td>{{ $call->patient->name }} {{ $call->patient->last_name }}</td>
                        <td>{{ $call->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($call->timestamp)->format('H:i') }}</td> <!-- Hora de la llamada -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
