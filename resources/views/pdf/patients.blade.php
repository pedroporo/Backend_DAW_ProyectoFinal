<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Lista de Pacientes</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Informe Lista de Pacientes</h2>
        <p>Fecha: {{ date('d-m-Y') }}</p>
    </div>
    <p><strong>Teleoperador:</strong> {{ $operatorName }}</p>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Nº Tarjeta Sanitaria</th>
                <th>Situación de Salud</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patients as $patient)
                <tr>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->last_name }}</td>
                    <td>{{ $patient->phone }}</td>
                    <td>{{ $patient->health_card_number }}</td>
                    <td>{{ $patient->health_situation }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
