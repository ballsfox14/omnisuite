<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Firmar Contrato</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-[#003366] mb-4">Firma de Contrato</h1>
            <div class="mb-4">
                <p><strong>Cliente:</strong> {{ $contract->client_name }}</p>
                <p><strong>Paquete:</strong> {{ $contract->package->name ?? 'No definido' }}</p>
                <p><strong>Zona:</strong> {{ $contract->zone->name ?? 'No definido' }}</p>
                <p><strong>Precio:</strong> ${{ number_format($contract->price, 2) }}</p>
            </div>
            <hr class="my-4">
            <p class="text-gray-700 mb-4">Por favor, firme a continuación para aceptar los términos del contrato.</p>

            <canvas id="signature-pad" width="400" height="200"
                style="border:1px solid #ccc; background: white;"></canvas>
            <div class="mt-2 flex gap-2">
                <button id="clear-signature" class="px-3 py-1 bg-gray-500 text-white rounded">Limpiar</button>
                <button id="save-signature" class="px-3 py-1 bg-green-600 text-white rounded">Guardar y firmar</button>
            </div>
            <form id="sign-form" method="POST"
                action="{{ route('contracts.public.sign.store', $contract->signature_token) }}">
                @csrf
                <input type="hidden" name="signature" id="signature-input">
            </form>
        </div>
    </div>
    <script>
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas);
        document.getElementById('clear-signature').addEventListener('click', function () {
            signaturePad.clear();
        });
        document.getElementById('save-signature').addEventListener('click', function () {
            if (signaturePad.isEmpty()) {
                alert('Por favor, dibuje su firma.');
            } else {
                var dataURL = signaturePad.toDataURL();
                document.getElementById('signature-input').value = dataURL;
                document.getElementById('sign-form').submit();
            }
        });
    </script>
</body>

</html>