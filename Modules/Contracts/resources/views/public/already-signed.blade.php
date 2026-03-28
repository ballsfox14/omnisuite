<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ya firmado</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <span class="material-icons text-red-500 text-6xl">info</span>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Contrato ya firmado</h1>
            <p class="text-gray-600 mt-2">Este contrato ya fue firmado anteriormente.</p>
            <p class="text-gray-500 mt-4">Fecha de firma: {{ $contract->signed_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>

</html>