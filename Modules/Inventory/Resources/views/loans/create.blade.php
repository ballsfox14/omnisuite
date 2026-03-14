<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">add_assign</span>
            {{ __('Nuevo Préstamo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.loans.store') }}" id="loanForm">
                        @csrf

                        <!-- Campo oculto con el ID del usuario logueado -->
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-md font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">people</span>
                                Equipo de trabajo
                            </h3>

                            <!-- Empleado principal (fijo) -->
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                    <span class="material-icons text-blue-600">person</span>
                                    Empleado principal
                                </p>
                                <p class="text-base font-semibold mt-1">
                                    {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                    @if(auth()->user()->area)
                                        <span class="text-sm font-normal text-gray-600"> -
                                            {{ auth()->user()->area->name }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Este empleado será el responsable principal del
                                    préstamo.</p>
                            </div>

                            <!-- Empleados adicionales -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 flex items-center gap-1 mb-2">
                                    <span class="material-icons text-gray-500">group_add</span> Empleados adicionales
                                </label>
                                <div id="additional-users-container">
                                    <!-- Los selects adicionales se insertarán aquí -->
                                </div>
                                <button type="button" id="add-user-btn" class="mt-2 text-sm"
                                    style="background-color: #003366; color: white; font-weight: bold; padding: 6px 12px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                    <span class="material-icons">add</span> Agregar empleado
                                </button>
                            </div>
                        </div>

                        <!-- Elementos del préstamo (herramientas/kits) -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-md font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">inventory</span>
                                Elementos a prestar
                            </h3>

                            <!-- Selector de tipo de elemento -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="loanable_type"
                                        class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <select id="loanable_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="tool">Herramienta</option>
                                        <option value="kit">Kit</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="loanable_id"
                                        class="block text-sm font-medium text-gray-700">Elemento</label>
                                    <select id="loanable_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Primero selecciona un tipo</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="quantity"
                                        class="block text-sm font-medium text-gray-700">Cantidad</label>
                                    <input type="number" id="quantity" min="1" value="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <button type="button" id="add-item-btn" class="mb-4"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 6px 12px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">add</span> Agregar elemento
                            </button>

                            <!-- Lista de elementos seleccionados -->
                            <div id="items-container">
                                <!-- Los items se mostrarán aquí -->
                            </div>
                        </div>

                        <!-- Fecha del préstamo y observaciones -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="loaned_at"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">event</span> Fecha de préstamo
                                    </label>
                                    <input type="date" name="loaned_at" id="loaned_at"
                                        value="{{ old('loaned_at', date('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('loaned_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="notes"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">note</span> Observaciones
                                    </label>
                                    <textarea name="notes" id="notes" rows="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('inventory.loans.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Registrar Préstamo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Datos para selects
            const users = @json($users);
            const toolsData = @json($tools);
            const kitsData = @json($kits);

            // Variables para índices
            let userIndex = 0;
            let itemIndex = 0;

            // Función para obtener opciones de empleados excluyendo los ya seleccionados
            function getAvailableUsers(excludeIds = []) {
                const principalId = {{ auth()->id() }}; // ID del usuario logueado (principal fijo)
                const allExcluded = [principalId, ...excludeIds];
                return users.filter(u => !allExcluded.includes(u.id));
            }

            // Agregar empleado adicional
            document.getElementById('add-user-btn').addEventListener('click', function () {
                const container = document.getElementById('additional-users-container');
                const currentAdditional = Array.from(container.querySelectorAll('select')).map(s => s.value);
                const available = getAvailableUsers(currentAdditional);

                if (available.length === 0) {
                    alert('No hay más empleados disponibles para agregar.');
                    return;
                }

                const div = document.createElement('div');
                div.className = 'flex items-center gap-2 mb-2';
                div.innerHTML = `
                    <select name="additional_users[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccionar empleado</option>
                        ${available.map(u => `<option value="${u.id}">${u.name} (${u.email}) - ${u.area?.name || 'Sin área'}</option>`).join('')}
                    </select>
                    <button type="button" class="remove-user text-red-600 hover:text-red-900">
                        <span class="material-icons">delete</span>
                    </button>
                `;
                container.appendChild(div);
            });

            // Eliminar empleado adicional
            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-user')) {
                    e.target.closest('.flex').remove();
                }
            });

            // Manejar cambio de tipo en elemento
            document.getElementById('loanable_type').addEventListener('change', function () {
                const type = this.value;
                const itemSelect = document.getElementById('loanable_id');
                itemSelect.innerHTML = '<option value="">Seleccionar elemento</option>';

                if (type === 'tool') {
                    toolsData.forEach(tool => {
                        const option = document.createElement('option');
                        option.value = tool.id;
                        option.textContent = `${tool.name} (${tool.code}) - Stock: ${tool.quantity}`;
                        itemSelect.appendChild(option);
                    });
                } else if (type === 'kit') {
                    kitsData.forEach(kit => {
                        const option = document.createElement('option');
                        option.value = kit.id;
                        option.textContent = `${kit.name} (${kit.code})`;
                        itemSelect.appendChild(option);
                    });
                }
            });

            // Agregar elemento a la lista
            document.getElementById('add-item-btn').addEventListener('click', function () {
                const type = document.getElementById('loanable_type').value;
                const itemId = document.getElementById('loanable_id').value;
                const quantity = document.getElementById('quantity').value;
                const itemName = document.getElementById('loanable_id').selectedOptions[0]?.text || '';

                if (!type || !itemId || !quantity) {
                    alert('Debes seleccionar tipo, elemento y cantidad.');
                    return;
                }

                const container = document.getElementById('items-container');
                const div = document.createElement('div');
                div.className = 'bg-gray-100 p-2 rounded flex items-center gap-4 mb-2';
                div.innerHTML = `
                    <input type="hidden" name="items[${itemIndex}][type]" value="${type}">
                    <input type="hidden" name="items[${itemIndex}][id]" value="${itemId}">
                    <input type="hidden" name="items[${itemIndex}][quantity]" value="${quantity}">
                    <span class="flex-1">${itemName} (x${quantity})</span>
                    <button type="button" class="remove-item text-red-600 hover:text-red-900">
                        <span class="material-icons">delete</span>
                    </button>
                `;
                container.appendChild(div);
                itemIndex++;

                // Resetear selects
                document.getElementById('loanable_type').value = '';
                document.getElementById('loanable_id').innerHTML = '<option value="">Primero selecciona un tipo</option>';
                document.getElementById('quantity').value = 1;
            });

            // Eliminar elemento de la lista
            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-item')) {
                    e.target.closest('.bg-gray-100').remove();
                }
            });
        </script>
    @endpush
</x-app-layout>