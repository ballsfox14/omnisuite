<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>
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
    <h2>Reporte de Asistencia</h2>
    <div class="subtitle">
        @if($user)
            Empleado: {{ $user->name }}
        @else
            Todos los empleados
        @endif
        <br>
        Período: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Fecha</th>
                <th>Entrada</th>
                <th>Inicio descanso</th>
                <th>Fin descanso</th>
                <th>Salida</th>
                <th>Horas trabajadas</th>
                <th>Esperado</th>
                <th>Extra</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->user->name }}</td>
                    <td>{{ $record->date->format('d/m/Y') }}</td>
                    <td>{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('h:i A') : '-' }}</td>
                    <td>{{ $record->break_start ? \Carbon\Carbon::parse($record->break_start)->format('h:i A') : '-' }}</td>
                    <td>{{ $record->break_end ? \Carbon\Carbon::parse($record->break_end)->format('h:i A') : '-' }}</td>
                    <td>{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '-' }}</td>
                    <td class="code">{{ $record->formatted_hours }}</td>
                    <td class="code">
                        {{ sprintf('%d:%02d', floor($record->expected_minutes / 60), $record->expected_minutes % 60) }}</td>
                    <td class="code">{{ $record->formatted_extra }}</td>
                    <td>{{ $record->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No hay registros para el período seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>