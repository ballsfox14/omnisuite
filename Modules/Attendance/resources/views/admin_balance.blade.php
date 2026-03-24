<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">analytics</span>
            {{ __('Balance de Asistencia') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Selector de empleado -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-200">
                <form method="GET" action="{{ route('attendance.admin.balance') }}"
                    class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                        <select name="user_id" id="user_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $emp->id == $user->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <span class="material-icons text-sm mr-1">search</span> Consultar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Información del empleado seleccionado -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-200">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-3xl text-gray-500">person</span>
                    <div>
                        <p class="text-sm text-gray-500">Empleado</p>
                        <p class="text-lg font-semibold">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }} | Código: {{ $user->employee_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Saldo acumulado del empleado -->
            <!-- Saldo acumulado del empleado (horizontal) -->
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
                    <span class="material-icons text-[#003366]">account_balance</span>
                    <h3 class="text-lg font-semibold text-gray-700">Saldo de horas</h3>
                </div>
                <div class="p-4 flex flex-wrap items-center justify-around gap-4">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-gray-500">attach_money</span>
                        <div>
                            <p class="text-xs text-gray-500">Saldo inicial</p>
                            <p class="text-xl font-bold">{{ number_format($user->initial_balance, 2) }} h</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-green-600">trending_up</span>
                        <div>
                            <p class="text-xs text-gray-500">Extra acumulado</p>
                            <p class="text-xl font-bold text-green-600">{{ number_format($user->total_credit, 2) }} h
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-red-600">trending_down</span>
                        <div>
                            <p class="text-xs text-gray-500">Déficit acumulado</p>
                            <p class="text-xl font-bold text-red-600">{{ number_format($user->total_debt, 2) }} h</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border-l pl-4">
                        <span
                            class="material-icons {{ $user->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">balance</span>
                        <div>
                            <p class="text-xs text-gray-500">Saldo neto total</p>
                            <p
                                class="text-xl font-bold {{ $user->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($user->net_balance, 2) }} h
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registro del día -->
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                        <span class="material-icons">event_note</span>
                        Registro del día {{ now()->format('d/m/Y') }}
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Evento</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hora</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $events = [
                                    'check_in' => 'Entrada principal',
                                    'break_start' => 'Salida para descanso',
                                    'break_end' => 'Reingreso de descanso',
                                    'check_out' => 'Salida final',
                                    'pause_start' => 'Inicio pausa',
                                    'pause_end' => 'Fin pausa',
                                ];
                            @endphp
                            @foreach($events as $field => $label)
                                @php
                                    $value = $dailyRecord ? $dailyRecord->$field : null;
                                    $time = $value ? \Carbon\Carbon::parse($value)->format('h:i A') : null;
                                    $status = $value ? 'Completado' : 'Pendiente';
                                    $statusClass = $value ? 'text-green-600' : 'text-gray-400';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $time ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $statusClass }}">{{ $status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Balance semanal -->
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                        <span class="material-icons">analytics</span>
                        Balance semanal ({{ $weekData['start_of_week']->format('d/m') }} -
                        {{ $weekData['end_of_week']->format('d/m') }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Día</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Esperado
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trabajado
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Diferencia
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weekData['week_data'] as $day)
                                @php
                                    $diffClass = $day['diff_hours'] > 0 ? 'text-green-600' : ($day['diff_hours'] < 0 ? 'text-red-600' : 'text-gray-500');
                                    $diffSign = $day['diff_hours'] > 0 ? '+' : '';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $day['date']->format('l d/m') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ sprintf('%d:%02d', floor($day['expected'] / 60), $day['expected'] % 60) }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $day['worked'] ? sprintf('%d:%02d', floor($day['worked'] / 60), $day['worked'] % 60) : '—' }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap {{ $diffClass }}">
                                        {{ $diffSign }}{{ number_format($day['diff_hours'], 2) }} h
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-right font-bold">
                    Balance neto:
                    <span
                        class="{{ $weekData['net_balance'] > 0 ? 'text-green-600' : ($weekData['net_balance'] < 0 ? 'text-red-600' : 'text-gray-500') }}">
                        {{ $weekData['net_balance'] > 0 ? '+' : '' }}{{ number_format($weekData['net_balance'], 2) }} h
                    </span>
                </div>
            </div>

            <!-- Historial de cierres -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                        <span class="material-icons">history</span>
                        Historial de cierres
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Semana</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Extra</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Déficit</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha cierre
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($weeklyHistory as $balance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $balance->week }}/{{ $balance->year }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-green-600">
                                        {{ number_format($balance->hours_extra, 2) }} h
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-red-600">
                                        {{ number_format($balance->hours_deficit, 2) }} h
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $balance->closed_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">No hay cierres registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>