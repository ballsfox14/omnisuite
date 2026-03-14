<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">visibility</span>
            {{ __('Detalle de Herramienta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">build</span>
                            {{ $tool->name }}
                        </h3>
                        <div class="flex gap-2">
                            <a href="{{ route('inventory.tools.edit', $tool) }}"
                                style="background-color: #ffc107; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">edit</span> Editar
                            </a>
                            <a href="{{ route('inventory.tools.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">arrow_back</span> Volver
                            </a>
                        </div>
                    </div>

                    <!-- Información principal en tarjetas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">tag</span> ID
                            </p>
                            <p class="mt-1 text-lg">{{ $tool->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">qr_code</span> Código
                            </p>
                            <p class="mt-1 text-lg font-mono">{{ $tool->code }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">category</span> Tipo
                            </p>
                            <p class="mt-1 text-lg">{{ $tool->tipo }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">trademark</span> Marca
                            </p>
                            <p class="mt-1 text-lg">{{ $tool->marca }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">model_training</span> Modelo
                            </p>
                            <p class="mt-1 text-lg">{{ $tool->modelo }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">calendar_today</span> Fecha de creación
                            </p>
                            <p class="mt-1 text-lg">{{ $tool->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">description</span> Descripción
                            </p>
                            <p class="mt-1">{{ $tool->description ?: 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <!-- Accesorios (si existen) -->
                    @if($tool->accesorios && count($tool->accesorios) > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-semibold mb-3 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">build</span>
                                Accesorios incluidos
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($tool->accesorios as $accesorio)
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 flex items-center gap-2">
                                        <span class="material-icons text-gray-500">check_circle</span>
                                        <span>{{ $accesorio }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>