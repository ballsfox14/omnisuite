<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos de Herramientas</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 20px;
            font-size: 10pt;
        }

        h1 {
            text-align: center;
            color: #003366;
            font-size: 16pt;
            margin-bottom: 10px;
        }

        .fecha {
            text-align: right;
            margin-bottom: 20px;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #003366;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        .codigo {
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }

        .page-break {
            page-break-after: always;
        }

        .small {
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Listado de Códigos de Herramientas</h1>
    <div class="fecha">Generado el: {{ $fecha }}</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tools as $tool)
                <tr>
                    <td>{{ $tool->id }}</td>
                    <td>{{ $tool->name }}</td>
                    <td class="codigo">{{ $tool->code }}</td>
                    <td>{{ $tool->tipo }}</td>
                    <td>{{ $tool->marca }}</td>
                    <td>{{ $tool->modelo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="small">
        Total de herramientas: {{ $tools->count() }}
    </div>
</body>

</html>