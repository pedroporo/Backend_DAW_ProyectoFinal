<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Llamadas de {{ $patient->name }} {{ $patient->last_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Historial de Llamadas de {{ $patient->name }} {{ $patient->last_name }}</h2>

    <h3>Llamadas Entrantes</h3>
    @if($incomingCalls->isEmpty())
        <p>No hay llamadas entrantes registradas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomingCalls as $call)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($call->timestamp)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($call->timestamp)->format('H:i') }}</td>
                        <td>{{ $call->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>Llamadas Salientes</h3>
    @if($outgoingCalls->isEmpty())
        <p>No hay llamadas salientes registradas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outgoingCalls as $call)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($call->timestamp)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($call->timestamp)->format('H:i') }}</td>
                        <td>{{ $call->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
