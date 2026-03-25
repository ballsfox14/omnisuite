<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">access_time</span>
            Historial de Asistencia
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Empleado</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Entrada</th>
                            <th class="px-4 py-2">Inicio descanso</th>
                            <th class="px-4 py-2">Fin descanso</th>
                            <th class="px-4 py-2">Salida</th>
                            <th class="px-4 py-2">Trabajado</th>
                            <th class="px-4 py-2">Esperado</th>
                            <th class="px-4 py-2">Extra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $r)
                            <tr>
                                <td class="px-4 py-2">{{ $r->user->name }}</td>
                                <td class="px-4 py-2">{{ $r->date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    {{ $r->check_in ? \Carbon\Carbon::parse($r->check_in)->format('h:i A') : '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ $r->break_start ? \Carbon\Carbon::parse($r->break_start)->format('h:i A') : '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $r->break_end ? \Carbon\Carbon::parse($r->break_end)->format('h:i A') : '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ $r->check_out ? \Carbon\Carbon::parse($r->check_out)->format('h:i A') : '-' }}</td>
                                <td class="px-4 py-2">{{ $r->formatted_hours }}</td>
                                <td class="px-4 py-2">
                                    {{ sprintf('%d:%02d', floor($r->expected_minutes / 60), $r->expected_minutes % 60) }}</td>
                                <td class="px-4 py-2">{{ $r->formatted_extra }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Sin registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $records->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>