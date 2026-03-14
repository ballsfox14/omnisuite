<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">qr_code_scanner</span>
            Préstamo rápido: {{ $kit->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('mobile.loan.store') }}">
                        @csrf
                        <input type="hidden" name="kit_id" value="{{ $kit->id }}">

                        <!-- Responsable principal (usuario logueado) -->
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                <span class="material-icons text-blue-600">person</span>
                                Responsable principal
                            </p>
                            <p class="text-base font-semibold mt-1">
                                {{ auth()->user()->name }}
                                <span class="text-sm font-normal text-gray-600">(tú)</span>
                            </p>
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        </div>

                        <!-- Responsables adicionales -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                                <span class="material-icons text-gray-500">group_add</span>
                                Acompañantes (opcional)
                            </label>
                            <div id="additional-users-container"></div>
                            <button type="button" id="add-user-btn"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer; margin-top: 8px;">
                                <span class="material-icons">add</span> Agregar acompañante
                            </button>
                        </div>

                        <!-- Fecha de préstamo -->
                        <div class="mb-4">
                            <label for="loan_date"
                                class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                <span class="material-icons text-gray-500">event</span>
                                Fecha de préstamo
                            </label>
                            <input type="date" name="loan_date" id="loan_date"
                                value="{{ old('loan_date', date('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                            @error('loan_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                <span class="material-icons text-gray-500">note</span>
                                Observaciones
                            </label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Elementos del kit (solo lectura) -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-medium text-gray-700 mb-2 flex items-center gap-1">
                                <span class="material-icons" style="color: #003366;">inventory</span>
                                Elementos del kit
                            </h4>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($kit->tools as $tool)
                                    <li>{{ $tool->name }} (x{{ $tool->pivot->quantity }}) - Código: {{ $tool->code }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Botón de envío -->
                        <button type="submit"
                            style="background-color: #28a745; color: white; font-weight: bold; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: none; cursor: pointer; width: 100%; font-size: 1.125rem;">
                            <span class="material-icons">assignment_turned_in</span>
                            Registrar Préstamo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Datos de usuarios (pasados desde el controlador)
            const users = @json($users);
            const principalId = {{ auth()->id() }};

            function getAvailableUsers(excludeIds = []) {
                const allExcluded = [principalId, ...excludeIds];
                return users.filter(u => !allExcluded.includes(u.id));
            }

            document.getElementById('add-user-btn').addEventListener('click', function () {
                const container = document.getElementById('additional-users-container');
                const currentAdditional = Array.from(container.querySelectorAll('select')).map(s => s.value);
                const available = getAvailableUsers(currentAdditional);

                if (available.length === 0) {
                    alert('No hay más empleados disponibles.');
                    return;
                }

                const div = document.createElement('div');
                div.className = 'flex gap-2 mb-2';
                div.innerHTML = `
                    <select name="additional_users[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                        <option value="">Seleccionar acompañante</option>
                        ${available.map(u => `<option value="${u.id}">${u.name} (${u.email})</option>`).join('')}
                    </select>
                    <button type="button" class="remove-user text-white bg-red-600 hover:bg-red-700 rounded px-2 py-1 flex items-center" style="background-color: #dc3545; border: none; cursor: pointer;">
                        <span class="material-icons">delete</span>
                    </button>
                `;
                container.appendChild(div);
            });

            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-user')) {
                    e.target.closest('.flex').remove();
                }
            });
        </script>
    @endpush
</x-app-layout>