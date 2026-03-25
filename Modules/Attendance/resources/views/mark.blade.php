<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">touch_app</span>
            Registro de Asistencia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Fecha actual -->
            <div class="text-center mb-6">
                <div class="text-2xl font-bold text-gray-800">{{ now()->format('l, j F Y') }}</div>
                <div class="text-gray-500">{{ now()->format('h:i:s A') }}</div>
            </div>

            <!-- Modo extraordinario -->
            @can('usar modo extraordinario')
            <div class="bg-white rounded-xl shadow-sm p-4 mb-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700">Modo extraordinario</span>
                        <button type="button" id="info-toggle" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <span class="material-icons text-base">info</span>
                        </button>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="extraordinary_mode" class="sr-only peer"
                            {{ $switchDisabled ? 'disabled' : '' }}
                            {{ $extraordinary ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-colors duration-200 ease-in-out"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out peer-checked:translate-x-5"></div>
                    </label>
                </div>
                <div id="info-panel" class="mt-3 hidden bg-blue-50 rounded-lg p-3 text-sm text-gray-700 border border-blue-200">
                    <p class="font-semibold mb-1 flex items-center gap-1"><span class="material-icons text-sm">info</span> ¿Cuándo usar este modo?</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Sábados, domingos o días festivos</li>
                        <li>Transmisiones especiales (elecciones, partidos)</li>
                        <li>Entrada tarde por consulta médica</li>
                        <li>Cualquier jornada que no requiera descanso obligatorio</li>
                    </ul>
                    <p class="text-xs text-gray-500 mt-2">Al activarlo, los botones de descanso se deshabilitan y no se descontará hora de descanso.</p>
                </div>
            </div>
            @endcan

            <!-- Modo pausa -->
            @can('usar modo pausa')
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700">Modo pausa</span>
                        <button type="button" id="pause-info-toggle" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <span class="material-icons text-base">info</span>
                        </button>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="pause_mode" class="sr-only peer"
                            {{ $switchDisabled ? 'disabled' : '' }}
                            {{ $pauseMode ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-colors duration-200 ease-in-out"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out peer-checked:translate-x-5"></div>
                    </label>
                </div>
                <div id="pause-info-panel" class="mt-3 hidden bg-blue-50 rounded-lg p-3 text-sm text-gray-700 border border-blue-200">
                    <p class="font-semibold mb-1 flex items-center gap-1"><span class="material-icons text-sm">info</span> ¿Cuándo usar este modo?</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Ausencias prolongadas (cita médica, trámites)</li>
                        <li>Jornada interrumpida sin tomar descanso</li>
                    </ul>
                    <p class="text-xs text-gray-500 mt-2">Al activarlo, los botones de descanso se deshabilitan y podrás usar los botones "Iniciar pausa" y "Finalizar pausa". El tiempo de pausa se restará automáticamente del total trabajado.</p>
                </div>
            </div>
            @endcan

            <!-- Saldo acumulado del empleado -->
  <!-- Saldo acumulado del empleado (horizontal) -->
<div class="bg-white rounded-xl shadow overflow-hidden mb-6">
    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
        <span class="material-icons text-[#003366]">account_balance</span>
        <h3 class="text-lg font-semibold text-gray-700">Mi saldo de horas</h3>
    </div>
    <div class="p-4 flex flex-wrap items-center justify-around gap-4">
        <div class="flex items-center gap-2">
            <span class="material-icons text-gray-500">attach_money</span>
            <div>
                <p class="text-xs text-gray-500">Saldo inicial</p>
                <p class="text-xl font-bold">{{ number_format(auth()->user()->initial_balance, 2) }} h</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-icons text-green-600">trending_up</span>
            <div>
                <p class="text-xs text-gray-500">Extra acumulado</p>
                <p class="text-xl font-bold text-green-600">{{ number_format(auth()->user()->total_credit, 2) }} h</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-icons text-red-600">trending_down</span>
            <div>
                <p class="text-xs text-gray-500">Déficit acumulado</p>
                <p class="text-xl font-bold text-red-600">{{ number_format(auth()->user()->total_debt, 2) }} h</p>
            </div>
        </div>
        <div class="flex items-center gap-2 border-l pl-4">
            <span class="material-icons {{ auth()->user()->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">balance</span>
            <div>
                <p class="text-xs text-gray-500">Saldo neto total</p>
                <p class="text-xl font-bold {{ auth()->user()->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format(auth()->user()->net_balance, 2) }} h
                </p>
            </div>
        </div>
    </div>
</div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-center">{{ $errors->first() }}</div>
            @endif

            <!-- Layout de dos columnas -->
            <div class="flex flex-col lg:flex-row gap-6 justify-center">
                <!-- Columna izquierda: acciones -->
                <div class="lg:w-1/3 space-y-3">
                    @php
                        $actions = [
                            'check-in' => ['label' => 'Entrada principal', 'icon' => 'login', 'color' => '#003366'],
                            'break-start' => ['label' => 'Salida para descanso', 'icon' => 'free_breakfast', 'color' => '#ffc107'],
                            'break-end' => ['label' => 'Reingreso de descanso', 'icon' => 'restaurant', 'color' => '#ffc107'],
                            'check-out' => ['label' => 'Salida final', 'icon' => 'logout', 'color' => '#28a745'],
                        ];
                    @endphp

                    @foreach($actions as $route => $action)
                        <form method="POST" action="{{ route("attendance.mark.$route") }}">
                            @csrf
                            <button type="submit"
                                style="background-color: {{ $action['color'] }};"
                                class="w-full py-4 px-5 rounded-xl shadow-md text-white font-semibold transition-all duration-200 flex items-center justify-between gap-3 hover:opacity-90 active:scale-95"
                                id="btn-{{ $route }}"
                                data-requires-break="{{ in_array($route, ['break-start', 'break-end']) ? '1' : '0' }}">
                                <div class="flex items-center gap-3">
                                    <span class="material-icons text-2xl">{{ $action['icon'] }}</span>
                                    <span>{{ $action['label'] }}</span>
                                </div>
                                @if($record && $record->{str_replace('-', '_', $route)})
                                    <span class="bg-white/20 text-xs font-mono px-2 py-1 rounded-full">
                                        {{ \Carbon\Carbon::parse($record->{str_replace('-', '_', $route)})->format('h:i A') }}
                                    </span>
                                @endif
                            </button>
                        </form>
                    @endforeach

                    <!-- Botones de pausa (solo visibles si el modo pausa está activo) -->
                    @can('usar modo pausa')
                    <div id="pause-buttons" class="mt-3 space-y-3" style="display: {{ $pauseMode ? 'block' : 'none' }};">
                        <div class="text-center text-sm font-semibold text-gray-500">Pausa larga</div>
                        <div class="grid grid-cols-2 gap-3">
                            <form method="POST" action="{{ route('attendance.mark.pause-start') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 px-3 rounded-xl bg-gray-500 text-white font-semibold hover:opacity-90 active:scale-95"
                                    {{ $record && $record->pause_start ? 'disabled' : '' }}
                                    style="{{ $record && $record->pause_start ? 'opacity:0.5' : '' }}">
                                    <span class="material-icons text-2xl">pause</span> Iniciar pausa
                                </button>
                            </form>
                            <form method="POST" action="{{ route('attendance.mark.pause-end') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 px-3 rounded-xl bg-gray-500 text-white font-semibold hover:opacity-90 active:scale-95"
                                    {{ (!$record || !$record->pause_start || $record->pause_end) ? 'disabled' : '' }}
                                    style="{{ (!$record || !$record->pause_start || $record->pause_end) ? 'opacity:0.5' : '' }}">
                                    <span class="material-icons text-2xl">play_arrow</span> Finalizar pausa
                                </button>
                            </form>
                        </div>
                    </div>
                    @endcan
                </div>

                <!-- Columna derecha: registro del día -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-xl shadow overflow-hidden">
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evento</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
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
                                            $value = $record ? $record->$field : null;
                                            $time = $value ? \Carbon\Carbon::parse($value)->format('h:i A') : null;
                                            $status = $value ? 'Completado' : 'Pendiente';
                                            $statusClass = $value ? 'text-green-600' : 'text-gray-400';
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $time ?: '—' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $statusClass }}">{{ $status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance semanal -->
            <div class="mt-8">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                            <span class="material-icons">analytics</span>
                            Balance semanal ({{ $weekData['start_of_week']->format('d/m') }} - {{ $weekData['end_of_week']->format('d/m') }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Día</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Esperado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trabajado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Diferencia</th>
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
                                        <td class="px-4 py-2 whitespace-nowrap">{{ sprintf('%d:%02d', floor($day['expected']/60), $day['expected']%60) }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $day['worked'] ? sprintf('%d:%02d', floor($day['worked']/60), $day['worked']%60) : '—' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap {{ $diffClass }}">{{ $diffSign }}{{ number_format($day['diff_hours'], 2) }} h</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-right font-bold">
                        Balance neto:
                        <span class="{{ $weekData['net_balance'] > 0 ? 'text-green-600' : ($weekData['net_balance'] < 0 ? 'text-red-600' : 'text-gray-500') }}">
                            {{ $weekData['net_balance'] > 0 ? '+' : '' }}{{ number_format($weekData['net_balance'], 2) }} h
                        </span>
                    </div>
                    @can('cerrar semanas')
                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-right">
                        <form method="POST" action="{{ route('attendance.mark.close-week') }}" class="inline">
                            @csrf
                            <input type="hidden" name="year" value="{{ now()->year }}">
                            <input type="hidden" name="week" value="{{ now()->weekOfYear }}">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                                Cerrar semana actual
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>
            </div>

            <!-- Historial de cierres -->
            <div class="mt-8">
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
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha cierre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($weeklyHistory as $balance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $balance->week }}/{{ $balance->year }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-green-600">{{ number_format($balance->hours_extra, 2) }} h</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-red-600">{{ number_format($balance->hours_deficit, 2) }} h</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $balance->closed_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No hay cierres registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botón de historial completo -->
            <div class="mt-10 text-center">
                <a href="{{ route('attendance.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-800 transition-all duration-200">
                    <span class="material-icons text-lg">history</span>
                    Ver historial completo
                </a>
            </div>
        </div>
    </div>

    @if(session('modal_data'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="fixed inset-0 flex items-center justify-center z-50 p-4" style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-green-600 flex items-center gap-2">
                    <span class="material-icons">check_circle</span>
                    {{ session('modal_data')['titulo'] }}
                </h3>
                <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="space-y-2">
                <p><strong>Horas trabajadas:</strong> {{ session('modal_data')['trabajado'] }}</p>
                <p><strong>Horas esperadas:</strong> {{ session('modal_data')['esperado'] }}</p>
                <p><strong>Horas extras netas:</strong> {{ session('modal_data')['extra'] }}</p>
                <p><strong>Tipo de jornada:</strong> {{ session('modal_data')['tipo_jornada'] }}</p>
            </div>
            <div class="mt-4 text-right">
                <button @click="show = false" class="px-4 py-2 bg-blue-600 text-white rounded">Cerrar</button>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const extraordinarySwitch = document.getElementById('extraordinary_mode');
            const pauseSwitch = document.getElementById('pause_mode');
            const breakStartBtn = document.getElementById('btn-break-start');
            const breakEndBtn = document.getElementById('btn-break-end');
            const pauseButtons = document.getElementById('pause-buttons');
            const infoToggle = document.getElementById('info-toggle');
            const infoPanel = document.getElementById('info-panel');
            const pauseInfoToggle = document.getElementById('pause-info-toggle');
            const pauseInfoPanel = document.getElementById('pause-info-panel');

            function updateUI() {
                const isExtraordinary = extraordinarySwitch ? extraordinarySwitch.checked : false;
                const isPause = pauseSwitch ? pauseSwitch.checked : false;

                if (breakStartBtn) {
                    breakStartBtn.disabled = isExtraordinary || isPause;
                    breakStartBtn.style.opacity = (isExtraordinary || isPause) ? '0.5' : '1';
                }
                if (breakEndBtn) {
                    breakEndBtn.disabled = isExtraordinary || isPause;
                    breakEndBtn.style.opacity = (isExtraordinary || isPause) ? '0.5' : '1';
                }

                if (pauseButtons) {
                    pauseButtons.style.display = isPause ? 'block' : 'none';
                }
            }

            function excludeModes() {
                if (!extraordinarySwitch || !pauseSwitch) return;

                extraordinarySwitch.addEventListener('change', function() {
                    if (extraordinarySwitch.checked && pauseSwitch.checked) {
                        pauseSwitch.checked = false;
                        pauseSwitch.dispatchEvent(new Event('change'));
                    }
                    updateUI();
                });

                pauseSwitch.addEventListener('change', function() {
                    if (pauseSwitch.checked && extraordinarySwitch.checked) {
                        extraordinarySwitch.checked = false;
                        extraordinarySwitch.dispatchEvent(new Event('change'));
                    }
                    updateUI();
                });
            }

            excludeModes();
            updateUI();

            if (infoToggle && infoPanel) {
                infoToggle.addEventListener('click', function() {
                    infoPanel.classList.toggle('hidden');
                });
            }
            if (pauseInfoToggle && pauseInfoPanel) {
                pauseInfoToggle.addEventListener('click', function() {
                    pauseInfoPanel.classList.toggle('hidden');
                });
            }

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (extraordinarySwitch) {
                        let hiddenExtra = document.createElement('input');
                        hiddenExtra.type = 'hidden';
                        hiddenExtra.name = 'extraordinary';
                        hiddenExtra.value = extraordinarySwitch.checked ? '1' : '0';
                        this.appendChild(hiddenExtra);
                    }
                    if (pauseSwitch) {
                        let hiddenPause = document.createElement('input');
                        hiddenPause.type = 'hidden';
                        hiddenPause.name = 'pause_mode';
                        hiddenPause.value = pauseSwitch.checked ? '1' : '0';
                        this.appendChild(hiddenPause);
                    }
                });
            });
        });
    </script>
</x-app-layout>