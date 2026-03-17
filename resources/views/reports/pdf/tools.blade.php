<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Herramientas</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #003366;
            color: white;
            padding: 8px;
        }

        td {
            border: 1px solid #ccc;
            padding: 6px;
        }
    </style>
</head>

<body>
    <h1>Listado de Herramientas</h1>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tools as $tool)
                <tr>
                    <td>{{ $tool->id }}</td>
                    <td>{{ $tool->name }}</td>
                    <td>{{ $tool->code }}</td>
                    <td>{{ $tool->tipo }}</td>
                    <td>{{ $tool->marca }}</td>
                    <td>{{ $tool->modelo }}</td>
                    <td>{{ $tool->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>