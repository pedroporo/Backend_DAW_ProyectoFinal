<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Emergencias</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        h2 {
            margin-top: 30px;
            color: #343a40;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

    <h1> Listado de Emergencias</h1>

    @foreach($zones as $zone)
        <h2> Zona: {{ $zone['zone_id'] }}</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Emergencia</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Paciente</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zone['emergencies'] as $emergency)
                    <tr>
                        <td>{{ $emergency['id'] }}</td>
                        <td>{{ $emergency['type'] }}</td>
                        <td>{{ $emergency['description'] }}</td>
                        <td>{{ $emergency['patient_name'] }}</td>
                        <td>{{ $emergency['timestamp'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
