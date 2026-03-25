<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">analytics</span>
            {{ __('Reporte de Asistencia Acumulado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Empleado</label>
                            <select id="user_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                <option value="">Todos los empleados</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha inicio</label>
                            <input type="date" id="start_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10"
                                value="{{ now()->startOfWeek()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha fin</label>
                            <input type="date" id="end_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10"
                                value="{{ now()->endOfWeek()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="flex gap-2 mb-6">
                        <button id="searchBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <span class="material-icons">search</span> Buscar
                        </button>
                    </div>

                    <!-- Previsualización del período actual -->
                    <div id="previewContainer" class="mb-8 hidden">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Resultados del período</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Fecha</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Entrada</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Inicio descanso</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fin
                                            descanso</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Salida</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Horas trabajadas</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                    <!-- AJAX results will populate here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-right font-bold" id="previewTotal"></div>
                        <div class="mt-2">
                            <button id="addPeriodBtn"
                                class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 flex items-center gap-1">
                                <span class="material-icons text-sm">add</span> Agregar este período
                            </button>
                        </div>
                    </div>

                    <!-- Períodos acumulados -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Períodos acumulados</h3>
                        <div id="accumulatedList" class="space-y-2">
                            <p class="text-gray-500 text-sm">Aún no hay períodos agregados.</p>
                        </div>
                        <div class="mt-2 text-right font-bold" id="totalAccumulated"></div>
                    </div>

                    <!-- Botones de exportación -->
                    <div class="flex justify-end gap-3 mt-6">
                        <form id="exportExcelForm" method="POST" action="{{ route('reports.attendance.multi.excel') }}">
                            @csrf
                            <input type="hidden" name="user_id" id="export_user_id">
                            <input type="hidden" name="periods" id="export_periods">
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                                <span class="material-icons">download</span> Exportar a Excel
                            </button>
                        </form>
                        <form id="exportPdfForm" method="POST" action="{{ route('reports.attendance.multi.pdf') }}">
                            @csrf
                            <input type="hidden" name="user_id" id="export_pdf_user_id">
                            <input type="hidden" name="periods" id="export_pdf_periods">
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                                <span class="material-icons">picture_as_pdf</span> Exportar a PDF
                            </button>
                        </form>
                        <a href="{{ route('reports.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Estado: lista de períodos acumulados
            let periods = [];

            // Función para renderizar la lista de períodos acumulados
            function renderAccumulatedList() {
                const container = document.getElementById('accumulatedList');
                const totalContainer = document.getElementById('totalAccumulated');
                if (periods.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-sm">Aún no hay períodos agregados.</p>';
                    totalContainer.innerHTML = '';
                    return;
                }

                let html = '';
                let totalMinutes = 0;
                periods.forEach((period, idx) => {
                    html += `
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 flex justify-between items-center">
                            <div>
                                <span class="font-medium">${period.start} al ${period.end}</span>
                                <span class="ml-2 text-sm text-gray-600">Total: ${period.total_hours}</span>
                            </div>
                            <button onclick="removePeriod(${idx})" class="text-red-600 hover:text-red-800">
                                <span class="material-icons">delete</span>
                            </button>
                        </div>
                    `;
                    // Sumar minutos (convertir formato HH:MM a minutos)
                    const [hours, minutes] = period.total_hours.split(':').map(Number);
                    totalMinutes += hours * 60 + (minutes || 0);
                });
                const totalHours = Math.floor(totalMinutes / 60);
                const totalMins = totalMinutes % 60;
                const totalFormatted = `${totalHours}:${totalMins.toString().padStart(2, '0')}`;
                container.innerHTML = html;
                totalContainer.innerHTML = `<span class="text-gray-700">Total acumulado: </span><span class="text-xl font-bold text-indigo-600">${totalFormatted}</span>`;
            }

            window.removePeriod = function (index) {
                periods.splice(index, 1);
                renderAccumulatedList();
            };

            // Búsqueda y previsualización
            document.getElementById('searchBtn').addEventListener('click', function () {
                const userId = document.getElementById('user_id').value;
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (!startDate || !endDate) {
                    alert('Debe seleccionar un rango de fechas.');
                    return;
                }

                fetch('{{ route('reports.attendance.search') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        start_date: startDate,
                        end_date: endDate
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('previewTableBody');
                        const totalSpan = document.getElementById('previewTotal');
                        tbody.innerHTML = '';
                        if (data.records.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">No hay registros en este período.</td></tr>';
                            totalSpan.innerHTML = '';
                        } else {
                            data.records.forEach(rec => {
                                const row = `<tr>
                                <td class="px-4 py-2">${rec.date}</td>
                                <td class="px-4 py-2">${rec.check_in}</td>
                                <td class="px-4 py-2">${rec.break_start}</td>
                                <td class="px-4 py-2">${rec.break_end}</td>
                                <td class="px-4 py-2">${rec.check_out}</td>
                                <td class="px-4 py-2">${rec.hours_worked}</td>
                            </tr>`;
                                tbody.innerHTML += row;
                            });
                            totalSpan.innerHTML = `Total horas en período: <span class="font-bold">${data.total_hours}</span>`;
                        }
                        document.getElementById('previewContainer').classList.remove('hidden');
                        // Guardar los datos del período actual para el botón agregar
                        window.currentPreview = {
                            start: startDate,
                            end: endDate,
                            total_hours: data.total_hours
                        };
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al buscar los datos.');
                    });
            });

            // Agregar período actual a la lista acumulada
            document.getElementById('addPeriodBtn').addEventListener('click', function () {
                if (!window.currentPreview) return;
                // Verificar que no se duplique el mismo rango
                const exists = periods.some(p => p.start === window.currentPreview.start && p.end === window.currentPreview.end);
                if (exists) {
                    alert('Este período ya ha sido agregado.');
                    return;
                }
                periods.push({
                    start: window.currentPreview.start,
                    end: window.currentPreview.end,
                    total_hours: window.currentPreview.total_hours
                });
                renderAccumulatedList();
            });

            // Función para preparar el formulario de exportación (ambos)
            function prepareExport(formId, periodsFieldId, userIdFieldId) {
                const userId = document.getElementById('user_id').value;
                const periodsData = periods.map(p => ({ start: p.start, end: p.end }));
                document.getElementById(userIdFieldId).value = userId;
                document.getElementById(periodsFieldId).value = JSON.stringify(periodsData);
                document.getElementById(formId).submit();
            }

            document.querySelector('#exportExcelForm').addEventListener('submit', function (e) {
                e.preventDefault();
                if (periods.length === 0) {
                    alert('Debe agregar al menos un período.');
                    return;
                }
                prepareExport('exportExcelForm', 'export_periods', 'export_user_id');
            });

            document.querySelector('#exportPdfForm').addEventListener('submit', function (e) {
                e.preventDefault();
                if (periods.length === 0) {
                    alert('Debe agregar al menos un período.');
                    return;
                }
                prepareExport('exportPdfForm', 'export_pdf_periods', 'export_pdf_user_id');
            });
        </script>
    @endpush
</x-app-layout>