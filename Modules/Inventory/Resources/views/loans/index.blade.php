<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">assignment</span>
            {{ __('Préstamos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">list</span>
                            Listado de Préstamos
                        </h3>
                        <a href="{{ route('inventory.loans.create') }}"
                            style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <span class="material-icons">add</span> Nuevo Préstamo
                        </a>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-4 flex items-center gap-2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <span class="material-icons">check_circle</span>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Responsable(s)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Elemento</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Préstamo</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($loans as $loan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loan->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $principal = $loan->user;
                                                $adicionales = $loan->users;
                                            @endphp
                                            @if($principal)
                                                {{ $principal->name }}
                                                @if($adicionales->count() > 0)
                                                    <span class="text-gray-500"> +{{ $adicionales->count() }} más</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">No especificado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($loan->items->count() == 1)
                                                @php $item = $loan->items->first(); @endphp
                                                @if($item->loanable_type == 'Modules\Inventory\Entities\Tool')
                                                    <span class="material-icons text-sm">build</span>
                                                @else
                                                    <span class="material-icons text-sm">inventory</span>
                                                @endif
                                                {{ $item->loanable->name ?? 'N/A' }}
                                            @else
                                                {{ $loan->items->count() }} elementos
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($loan->items->count() == 1)
                                                {{ $loan->items->first()->quantity }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loan->loaned_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColor = $loan->isActive() ? 'yellow' : 'green';
                                                $statusText = $loan->isActive() ? 'Prestado' : 'Devuelto';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('inventory.loans.show', $loan) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-2">Ver</a>
                                            @if($loan->isActive())
                                                <form action="{{ route('inventory.loans.return', $loan) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900"
                                                        onclick="return confirm('¿Registrar devolución?')">Devolver</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay préstamos
                                            registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $loans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>