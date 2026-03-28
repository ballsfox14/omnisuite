<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">add</span>
            {{ __('Nuevo Contrato') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('contracts.store') }}" id="contractForm">
                        @csrf

                        <!-- Datos del cliente -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">person</span>
                                Datos del cliente
                            </h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="client_name"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">person</span> Nombre del cliente
                                    </label>
                                    <input type="text" name="client_name" id="client_name"
                                        value="{{ old('client_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('client_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del servicio -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">shopping_cart</span>
                                Detalles del servicio
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="package_id"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">package</span> Paquete
                                    </label>
                                    <select name="package_id" id="package_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                        <option value="">Seleccionar paquete</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="zone_id"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">location_on</span> Zona
                                    </label>
                                    <select name="zone_id" id="zone_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                        <option value="">Seleccionar zona</option>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('zone_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="price"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">attach_money</span> Precio
                                    </label>
                                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10"
                                        readonly>
                                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Firma digital -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">edit</span>
                                Firma digital
                            </h3>
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 mb-2">Firmar ahora:</p>
                                    <canvas id="signature-pad" width="400" height="200"
                                        style="border:1px solid #ccc; background: white;"></canvas>
                                    <div class="mt-2">
                                        <button type="button" id="clear-signature"
                                            class="px-3 py-1 bg-gray-500 text-white rounded text-sm">Limpiar</button>
                                    </div>
                                    <input type="hidden" name="signature" id="signature-input">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                                    <div id="signature-preview"
                                        class="border border-gray-200 bg-white p-2 min-h-[120px] flex items-center justify-center text-gray-400">
                                        No hay firma guardada.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('contracts.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
        <script>
            var canvas = document.getElementById('signature-pad');
            var signaturePad = new SignaturePad(canvas);
            var previewDiv = document.getElementById('signature-preview');
            var signatureInput = document.getElementById('signature-input');
            var form = document.getElementById('contractForm');

            signaturePad.addEventListener('endStroke', function () {
                if (!signaturePad.isEmpty()) {
                    var dataURL = signaturePad.toDataURL();
                    previewDiv.innerHTML = '<img src="' + dataURL + '" style="max-width:100%; max-height:100px;">';
                    signatureInput.value = dataURL;
                } else {
                    previewDiv.innerHTML = '<span class="text-gray-400">No hay firma guardada.</span>';
                    signatureInput.value = '';
                }
            });

            document.getElementById('clear-signature').addEventListener('click', function () {
                signaturePad.clear();
                previewDiv.innerHTML = '<span class="text-gray-400">No hay firma guardada.</span>';
                signatureInput.value = '';
            });

            form.addEventListener('submit', function (e) {
                if (!signaturePad.isEmpty() && !signatureInput.value) {
                    signatureInput.value = signaturePad.toDataURL();
                    previewDiv.innerHTML = '<img src="' + signatureInput.value + '" style="max-width:100%; max-height:100px;">';
                }
            });
        </script>

        <script>
            const packageSelect = document.getElementById('package_id');
            const zoneSelect = document.getElementById('zone_id');
            const priceInput = document.getElementById('price');

            function fetchPrice() {
                const packageId = packageSelect.value;
                const zoneId = zoneSelect.value;
                if (!packageId || !zoneId) {
                    priceInput.value = '';
                    return;
                }

                fetch('{{ route('contracts.get-price') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ package_id: packageId, zone_id: zoneId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.price) {
                            priceInput.value = data.price;
                        } else {
                            priceInput.value = '';
                            alert('No se encontró precio para la combinación seleccionada.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        priceInput.value = '';
                    });
            }

            packageSelect.addEventListener('change', fetchPrice);
            zoneSelect.addEventListener('change', fetchPrice);
        </script>
    @endpush
</x-app-layout>