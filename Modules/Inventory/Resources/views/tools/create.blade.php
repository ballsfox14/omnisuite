<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">add</span>
            {{ __('Nueva Herramienta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.tools.store') }}" id="toolForm">
                        @csrf

                        <!-- Datos básicos -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-md font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">info</span>
                                Datos de la Herramienta
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Nombre -->
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <span class="material-icons text-gray-500"
                                                style="font-size: 1.25rem;">badge</span>
                                            Nombre
                                        </span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <span class="material-icons text-gray-500"
                                                style="font-size: 1.25rem;">category</span>
                                            Tipo
                                        </span>
                                    </label>
                                    <input type="text" name="tipo" id="tipo" value="{{ old('tipo') }}"
                                        placeholder="ej: CAM, MIC"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('tipo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Marca -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <span class="material-icons text-gray-500"
                                                style="font-size: 1.25rem;">trademark</span>
                                            Marca
                                        </span>
                                    </label>
                                    <input type="text" name="marca" id="marca" value="{{ old('marca') }}"
                                        placeholder="ej: DJI, SONY"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('marca') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Modelo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <span class="material-icons text-gray-500"
                                                style="font-size: 1.25rem;">model_training</span>
                                            Modelo
                                        </span>
                                    </label>
                                    <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}"
                                        placeholder="ej: DMT02"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('modelo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Cantidad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <span class="material-icons text-gray-500"
                                                style="font-size: 1.25rem;">inventory</span>
                                            Cantidad
                                        </span>
                                    </label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                                        min="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Descripción (ancho completo) -->
                                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <span class="material-icons text-gray-500"
                                                style="font-size: 1.25rem;">description</span>
                                            Descripción
                                        </span>
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Accesorios (campo de texto repetible) -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-md font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">build</span>
                                Accesorios (opcional)
                            </h3>

                            <div id="accesorios-container">
                                <!-- Los campos se agregarán aquí dinámicamente -->
                            </div>

                            <button type="button" id="add-accesorio-btn"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer; margin-top: 8px;">
                                <span class="material-icons">add</span> Agregar accesorio
                            </button>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('inventory.tools.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Guardar Herramienta(s)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let accIndex = 0;
            const container = document.getElementById('accesorios-container');

            document.getElementById('add-accesorio-btn').addEventListener('click', function () {
                const div = document.createElement('div');
                div.className = 'flex gap-2 mb-2 items-center';
                div.innerHTML = `
                    <input type="text" name="accesorios[${accIndex}]" placeholder="Nombre del accesorio" 
                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                    <button type="button" class="remove-accesorio text-white bg-red-600 hover:bg-red-700 rounded px-3 py-2 flex items-center justify-center h-10"
                            style="background-color: #dc3545; border: none; cursor: pointer;">
                        <span class="material-icons">delete</span>
                    </button>
                `;
                container.appendChild(div);
                accIndex++;
            });

            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-accesorio')) {
                    e.target.closest('.flex').remove();
                }
            });
        </script>
    @endpush
</x-app-layout>