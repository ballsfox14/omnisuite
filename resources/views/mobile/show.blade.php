<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">visibility</span>
            {{ __('Detalle de Kit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">inventory</span>
                            {{ $kit->name }}
                        </h3>
                        <div class="flex gap-2">
                            <a href="{{ route('inventory.kits.edit', $kit) }}"
                                style="background-color: #ffc107; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">edit</span> Editar
                            </a>
                            <a href="{{ route('inventory.kits.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">arrow_back</span> Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">tag</span> ID
                            </p>
                            <p class="mt-1 text-lg">{{ $kit->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">qr_code</span> Código
                            </p>
                            <p class="mt-1 text-lg">{{ $kit->code }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">calendar_today</span> Fecha de creación
                            </p>
                            <p class="mt-1 text-lg">{{ $kit->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">description</span> Descripción
                            </p>
                            <p class="mt-1">{{ $kit->description ?: 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <h4 class="text-md font-semibold mt-4 mb-2 flex items-center gap-2">
                        <span class="material-icons" style="color: #003366;">build</span>
                        Herramientas incluidas
                    </h4>
                    @if($kit->tools->count() > 0)
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Herramienta</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Código
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($kit->tools as $tool)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $tool->name }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $tool->code }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $tool->pivot->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 mb-6">Este kit no contiene herramientas.</p>
                    @endif

                    <!-- Código QR del kit -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                        <h4 class="font-medium text-gray-700 mb-3 flex items-center justify-center gap-1">
                            <span class="material-icons" style="color: #003366;">qr_code</span>
                            Código QR del kit
                        </h4>
                        <div class="flex justify-center">
                            {!! $kit->qr_code !!}
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Escanea con tu móvil para préstamo rápido
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            URL: {{ $kit->mobile_url }}
                        </p>

                        <!-- Botón de descarga del QR -->
                        <a href="data:image/svg+xml,{{ rawurlencode($kit->qr_code) }}"
                            download="kit-{{ $kit->code }}.svg"
                            class="mt-3 inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                            <span class="material-icons">download</span>
                            Descargar QR (SVG)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>