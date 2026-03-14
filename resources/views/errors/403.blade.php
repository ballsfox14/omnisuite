<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Acción no autorizada</title>
    <!-- Material Icons (opcional, si quieres usar iconos) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Tailwind (puedes usar el mismo CSS que tu app) -->
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .error-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            text-align: center;
        }

        .error-header {
            background-color: #003366;
            color: white;
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .error-body {
            padding: 2rem;
        }

        .meme-container {
            margin: 1.5rem 0;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .meme-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        .btn-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #003366;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-dashboard:hover {
            background-color: #002244;
        }

        .btn-dashboard i {
            font-size: 1.25rem;
        }

        .error-code {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-header">
            <span class="material-icons">gpp_bad</span>
            <span>¡Ups! Acción no autorizada</span>
        </div>
        <div class="error-body">
            <div class="error-code">
                Código 403 - No tienes permiso para realizar esta acción.
            </div>
            <!-- Aquí va tu meme. Cambia la ruta por la ubicación real de tu imagen -->
            <div class="meme-container">
                <img src="{{ asset('images/meme.webp') }}" alt="Meme personalizado">
                <!-- Si no tienes imagen, puedes usar un placeholder o un emoji grande -->
            </div>
            <a href="{{ route('dashboard') }}" class="btn-dashboard">
                <span class="material-icons">dashboard</span>
                Volver al Dashboard
            </a>
        </div>
    </div>
</body>

</html>