<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">history</span>
            {{ __('Historial de Actividades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">person</span> Usuario
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">label</span> Acción
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">description</span> Descripción
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">event</span> Fecha
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">visibility</span> Detalles
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($activities as $activity)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $activity->causer->name ?? 'Sistema' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $action = $activity->description;
                                                $badgeColor = match ($action) {
                                                    'created' => 'green',
                                                    'updated' => 'yellow',
                                                    'deleted' => 'red',
                                                    default => 'blue',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800">
                                                {{ $action }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($activity->subject)
                                                <span class="font-medium">{{ class_basename($activity->subject_type) }}</span>
                                                @if($activity->subject->name ?? false)
                                                    <span class="text-gray-600">: {{ $activity->subject->name }}</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">Sistema</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="openModal({{ $activity->id }})"
                                                class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                                                <span class="material-icons">open_in_new</span>
                                            </button>

                                            <!-- Modal oculto por actividad -->
                                            <div id="modal-{{ $activity->id }}"
                                                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50"
                                                onclick="if(event.target === this) closeModal({{ $activity->id }})">
                                                <div
                                                    class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h3
                                                            class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                                            <span class="material-icons text-[#003366]">info</span>
                                                            Detalles del registro
                                                        </h3>
                                                        <button onclick="closeModal({{ $activity->id }})"
                                                            class="text-gray-400 hover:text-gray-600">
                                                            <span class="material-icons">close</span>
                                                        </button>
                                                    </div>
                                                    <div class="mt-2 space-y-3">
                                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                                            <span class="font-medium text-gray-500">ID:</span>
                                                            <span class="col-span-2">{{ $activity->id }}</span>

                                                            <span class="font-medium text-gray-500">Usuario:</span>
                                                            <span
                                                                class="col-span-2">{{ $activity->causer->name ?? 'Sistema' }}</span>

                                                            <span class="font-medium text-gray-500">Acción:</span>
                                                            <span class="col-span-2">{{ $activity->description }}</span>

                                                            <span class="font-medium text-gray-500">Modelo:</span>
                                                            <span
                                                                class="col-span-2">{{ $activity->subject_type ? class_basename($activity->subject_type) : 'N/A' }}</span>

                                                            <span class="font-medium text-gray-500">ID del modelo:</span>
                                                            <span
                                                                class="col-span-2">{{ $activity->subject_id ?? 'N/A' }}</span>

                                                            <span class="font-medium text-gray-500">Fecha:</span>
                                                            <span
                                                                class="col-span-2">{{ $activity->created_at->format('d/m/Y H:i:s') }}</span>
                                                        </div>

                                                        <div class="mt-4">
                                                            <p
                                                                class="font-medium text-gray-700 mb-2 flex items-center gap-1">
                                                                <span class="material-icons text-sm">data_object</span>
                                                                Datos completos:
                                                            </p>
                                                            <pre
                                                                class="bg-gray-50 p-3 rounded border border-gray-200 text-xs overflow-auto max-h-60">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            <span class="material-icons align-middle mr-1">info</span> No hay registros de
                                            actividad.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
        }
        // Cerrar con tecla Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>