<!DOCTYPE html>
<html>
<head>
    <title>Llamadas realizadas</title>
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
    <h1>Llamadas realizadas el {{ $date }}</h1>
    <h2>Operador: {{ $operatorName }}</h2>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Fecha y Hora</th>
                <th>Alerta</th>
                <th>Planeada</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($outgoingCalls as $call)
                @php
            $isPlanned = $call->is_planned ? 'Sí' : 'No';
                @endphp
                <tr>
                    <td>{{ $call->id }}</td>
                    <td>{{ $call->patient->name ?? 'N/A' }}</td>
                    <td>{{ $call->timestamp }}</td>
                    <td>{{ $isPlanned }}</td>
                    <td>{{ $call->alert ? 'Sí (ID: ' . $call->alert->id . ')' : 'No' }}</td>
                    <td>{{ $call->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
