<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">edit</span>
            {{ __('Editar Kit') }}: {{ $kit->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.kits.update', $kit) }}" id="kitForm">
                        @csrf @method('PUT')

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-md font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">info</span>
                                Información del Kit
                            </h3>
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">badge</span> Nombre
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $kit->name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">description</span> Descripción
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $kit->description) }}</textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Selección de herramientas -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-md font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">build</span>
                                Herramientas del Kit
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="tool_select"
                                        class="block text-sm font-medium text-gray-700">Herramienta</label>
                                    <select id="tool_select"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                        <option value="">Seleccionar herramienta</option>
                                        @foreach ($tools as $tool)
                                            <option value="{{ $tool->id }}" data-name="{{ $tool->name }}"
                                                data-code="{{ $tool->code }}">
                                                {{ $tool->name }} ({{ $tool->code }}) - Stock: {{ $tool->quantity }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tool_quantity"
                                        class="block text-sm font-medium text-gray-700">Cantidad</label>
                                    <input type="number" id="tool_quantity" min="1" value="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" id="add-tool-btn"
                                        style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer; height: 40px;">
                                        <span class="material-icons">add</span> Agregar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabla de herramientas seleccionadas -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Herramienta</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Código</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Cantidad</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected-tools-table" class="bg-white divide-y divide-gray-200">
                                        <!-- Aquí se cargarán las herramientas existentes -->
                                    </tbody>
                                </table>
                                <p class="text-sm text-gray-500 mt-2" id="no-tools-message" style="display: none;">No
                                    hay herramientas agregadas.</p>
                            </div>

                            <!-- Contenedor para campos ocultos que se enviarán -->
                            <div id="tools-hidden-fields"></div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('inventory.kits.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Actualizar Kit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const toolsData = @json($tools);
            const existingTools = @json($kit->tools);
            let toolIndex = existingTools.length;
            const tableBody = document.getElementById('selected-tools-table');
            const noToolsMsg = document.getElementById('no-tools-message');
            const hiddenContainer = document.getElementById('tools-hidden-fields');

            // Cargar herramientas existentes
            function loadExistingTools() {
                existingTools.forEach((tool, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${tool.name}</td>
                        <td class="px-4 py-2">${tool.code}</td>
                        <td class="px-4 py-2">${tool.pivot.quantity}</td>
                        <td class="px-4 py-2">
                            <button type="button" class="remove-tool text-red-600 hover:text-red-900">
                                <span class="material-icons">delete</span>
                            </button>
                        </td>
                    `;
                    row.dataset.toolId = tool.id;
                    row.dataset.quantity = tool.pivot.quantity;
                    tableBody.appendChild(row);

                    // Hidden fields
                    const hidId = document.createElement('input');
                    hidId.type = 'hidden';
                    hidId.name = `tools[${index}][id]`;
                    hidId.value = tool.id;
                    hiddenContainer.appendChild(hidId);

                    const hidQty = document.createElement('input');
                    hidQty.type = 'hidden';
                    hidQty.name = `tools[${index}][quantity]`;
                    hidQty.value = tool.pivot.quantity;
                    hiddenContainer.appendChild(hidQty);
                });

                if (existingTools.length === 0) {
                    noToolsMsg.style.display = 'block';
                }
            }

            loadExistingTools();

            document.getElementById('add-tool-btn').addEventListener('click', function () {
                const select = document.getElementById('tool_select');
                const quantity = document.getElementById('tool_quantity').value;
                const selectedOption = select.options[select.selectedIndex];

                if (!select.value || !quantity) {
                    alert('Debes seleccionar una herramienta y especificar cantidad.');
                    return;
                }

                const toolId = select.value;
                const toolName = selectedOption.dataset.name;
                const toolCode = selectedOption.dataset.code;

                // Crear fila
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${toolName}</td>
                    <td class="px-4 py-2">${toolCode}</td>
                    <td class="px-4 py-2">${quantity}</td>
                    <td class="px-4 py-2">
                        <button type="button" class="remove-tool text-red-600 hover:text-red-900">
                            <span class="material-icons">delete</span>
                        </button>
                    </td>
                `;
                row.dataset.toolId = toolId;
                row.dataset.quantity = quantity;
                tableBody.appendChild(row);

                // Agregar hidden fields
                const hidId = document.createElement('input');
                hidId.type = 'hidden';
                hidId.name = `tools[${toolIndex}][id]`;
                hidId.value = toolId;
                hiddenContainer.appendChild(hidId);

                const hidQty = document.createElement('input');
                hidQty.type = 'hidden';
                hidQty.name = `tools[${toolIndex}][quantity]`;
                hidQty.value = quantity;
                hiddenContainer.appendChild(hidQty);

                toolIndex++;
                noToolsMsg.style.display = 'none';

                // Reset
                select.value = '';
                document.getElementById('tool_quantity').value = '1';
            });

            // Eliminar herramienta
            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-tool')) {
                    const row = e.target.closest('tr');
                    // Para simplificar, regeneramos todos los hidden fields desde las filas actuales
                    row.remove();
                    const rows = tableBody.querySelectorAll('tr');
                    hiddenContainer.innerHTML = '';
                    toolIndex = 0;
                    rows.forEach(r => {
                        const id = r.dataset.toolId;
                        const qty = r.dataset.quantity;
                        if (id && qty) {
                            const hidId = document.createElement('input');
                            hidId.type = 'hidden';
                            hidId.name = `tools[${toolIndex}][id]`;
                            hidId.value = id;
                            hiddenContainer.appendChild(hidId);

                            const hidQty = document.createElement('input');
                            hidQty.type = 'hidden';
                            hidQty.name = `tools[${toolIndex}][quantity]`;
                            hidQty.value = qty;
                            hiddenContainer.appendChild(hidQty);
                            toolIndex++;
                        }
                    });

                    if (rows.length === 0) {
                        noToolsMsg.style.display = 'block';
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>