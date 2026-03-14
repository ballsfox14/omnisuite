<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">visibility</span>
            {{ __('Detalle del Préstamo') }} #{{ $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">assignment</span>
                            Préstamo #{{ $loan->id }}
                        </h3>
                        <div class="flex gap-2">
                            @if($loan->isActive())
                                <form action="{{ route('inventory.loans.return', $loan) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                        <span class="material-icons">assignment_return</span> Devolver
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('inventory.loans.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">arrow_back</span> Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Empleado principal -->
                        @if($loan->user)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                    <span class="material-icons text-gray-400">person</span> Empleado principal
                                </p>
                                <p class="mt-1 text-lg">{{ $loan->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $loan->user->email }}</p>
                                @if($loan->user->area)
                                    <p class="text-sm text-gray-500">{{ $loan->user->area->name }}</p>
                                @endif
                            </div>
                        @endif

                        <!-- Empleados adicionales -->
                        @if($loan->users->count() > 0)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                    <span class="material-icons text-gray-400">group</span> Empleados adicionales
                                </p>
                                <div class="mt-2 space-y-1">
                                    @foreach($loan->users as $user)
                                        <div class="flex items-center gap-2">
                                            <span class="material-icons text-sm">person</span>
                                            {{ $user->name }} ({{ $user->email }})
                                            @if($user->area)
                                                <span class="text-xs text-gray-500">{{ $user->area->name }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Fecha de préstamo -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">event</span> Fecha de préstamo
                            </p>
                            <p class="mt-1 text-lg">{{ $loan->loaned_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <!-- Fecha de devolución (si aplica) -->
                        @if($loan->returned_at)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                    <span class="material-icons text-gray-400">assignment_turned_in</span> Fecha de
                                    devolución
                                </p>
                                <p class="mt-1 text-lg">{{ $loan->returned_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif

                        <!-- Estado -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">info</span> Estado
                            </p>
                            <p class="mt-1">
                                @php
                                    $statusColor = $loan->isActive() ? 'yellow' : 'green';
                                    $statusText = $loan->isActive() ? 'Prestado' : 'Devuelto';
                                @endphp
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                    {{ $statusText }}
                                </span>
                            </p>
                        </div>

                        <!-- Observaciones -->
                        @if($loan->notes)
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                    <span class="material-icons text-gray-400">note</span> Observaciones
                                </p>
                                <p class="mt-1">{{ $loan->notes }}</p>
                            </div>
                        @endif

                        <!-- Elementos prestados -->
                        <div class="md:col-span-2">
                            <h4 class="text-md font-semibold mt-4 mb-2 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">list</span>
                                Elementos prestados
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Tipo</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Nombre</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Código</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($loan->items as $item)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    @if($item->loanable_type == 'Modules\Inventory\Entities\Tool')
                                                        <span class="material-icons">build</span>
                                                    @else
                                                        <span class="material-icons">inventory</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2">{{ $item->loanable->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">{{ $item->loanable->code ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>