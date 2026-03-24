<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Códigos de Herramientas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 10px;
            margin: 20px;
            color: #1e293b;
            line-height: 1.4;
        }

        h2 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            text-align: center;
            color: #003366;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #003366;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: 500;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        td {
            border: 1px solid #e2e8f0;
            padding: 6px;
            font-size: 9px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
            color: #6c757d;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }

        .code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 8.5px;
        }
    </style>
</head>

<body>
    <h2>Listado de Códigos de Herramientas</h2>
    <div class="subtitle">
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>

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
                    <td class="code">{{ $tool->code }}</td>
                    <td>{{ $tool->tipo }}</td>
                    <td>{{ $tool->marca }}</td>
                    <td>{{ $tool->modelo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total de herramientas: {{ $tools->count() }}
    </div>
</body>

</html>