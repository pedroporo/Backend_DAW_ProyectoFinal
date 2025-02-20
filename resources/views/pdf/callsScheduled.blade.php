<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llamadas Planificadas</title>
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
    <h1>Llamadas Planificadas de {{ $user->name }}</h1>
    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Alarma</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($scheduledCalls as $call)
                <tr>
                    <td>{{ $call->patient->name }} {{ $call->patient->last_name }}</td>
                    <td>{{ $call->description }}</td>
                    <td>{{ $call->timestamp }}</td>
                    <td>{{ $call->alert ? 'Sí (ID: ' . $call->alert->id . ')' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
