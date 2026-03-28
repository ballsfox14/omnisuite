<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">visibility</span>
            {{ __('Detalle del Contrato') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">description</span>
                            {{ $contract->client_name }}
                        </h3>
                        <div class="flex gap-2">
                            @can('editar contratos')
                                <a href="{{ route('contracts.edit', $contract) }}"
                                    style="background-color: #ffc107; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <span class="material-icons">edit</span> Editar
                                </a>
                            @endcan
                            <a href="{{ route('contracts.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">arrow_back</span> Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">tag</span> ID
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">person</span> Cliente
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->client_name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">admin_panel_settings</span> Creado por
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->creator->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">info</span> Estado
                            </p>
                            <p class="mt-1">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $contract->status_color }}-100 text-{{ $contract->status_color }}-800">
                                    {{ $contract->status_label }}
                                </span>
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">package</span> Paquete
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->package->name ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">location_on</span> Zona
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->zone->name ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">attach_money</span> Precio
                            </p>
                            <p class="mt-1 text-lg">
                                {{ $contract->price ? '$' . number_format($contract->price, 2) : '—' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">calendar_today</span> Fecha de creación
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">update</span> Última actualización
                            </p>
                            <p class="mt-1 text-lg">{{ $contract->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($contract->signature)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="font-medium text-gray-700 mb-2 flex items-center gap-1">
                                <span class="material-icons text-gray-500">edit</span> Firma digital
                            </h4>
                            <img src="{{ $contract->signature }}" alt="Firma"
                                style="max-width: 300px; border:1px solid #ccc;">
                            <p class="text-xs text-gray-500 mt-1">Firmado el:
                                {{ $contract->signature_date ? $contract->signature_date->format('d/m/Y H:i') : '-' }}</p>
                            <p class="text-xs text-gray-500">Método:
                                {{ $contract->signature_method == 'panel' ? 'Desde el panel' : 'Público' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>