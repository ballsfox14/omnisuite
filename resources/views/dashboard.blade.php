<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="material-icons" style="color: #003366;">dashboard</span>
                {{ __('Dashboard') }}
            </h2>
            <p class="text-sm text-gray-600">Bienvenido, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Herramientas -->
                <div style="background-color: #2563eb; color: white;"
                    class="rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-wider opacity-90">Herramientas</p>
                        <p class="text-3xl font-bold">{{ $totalTools }}</p>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.2);" class="rounded-full p-3">
                        <span class="material-icons text-4xl">build</span>
                    </div>
                </div>

                <!-- Total Kits -->
                <div style="background-color: #16a34a; color: white;"
                    class="rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-wider opacity-90">Kits</p>
                        <p class="text-3xl font-bold">{{ $totalKits }}</p>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.2);" class="rounded-full p-3">
                        <span class="material-icons text-4xl">inventory</span>
                    </div>
                </div>

                <!-- Préstamos activos -->
                <div style="background-color: #ca8a04; color: white;"
                    class="rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-wider opacity-90">Préstamos activos</p>
                        <p class="text-3xl font-bold">{{ $activeLoans }}</p>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.2);" class="rounded-full p-3">
                        <span class="material-icons text-4xl">assignment</span>
                    </div>
                </div>

                <!-- Vencidos -->
                <div style="background-color: #dc2626; color: white;"
                    class="rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-wider opacity-90">Vencidos</p>
                        <p class="text-3xl font-bold">{{ $overdueLoans }}</p>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.2);" class="rounded-full p-3">
                        <span class="material-icons text-4xl">warning</span>
                    </div>
                </div>
            </div>

            <!-- Últimos préstamos -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <span class="material-icons" style="color: #003366;">history</span>
                        Últimos préstamos
                    </h3>
                    <a href="{{ route('inventory.loans.index') }}" style="color: #003366;"
                        class="text-sm hover:underline flex items-center gap-1">
                        Ver todos <span class="material-icons text-sm">arrow_forward</span>
                    </a>
                </div>
                @if($recentLoans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Responsable</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Elemento</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentLoans as $loan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div style="background-color: #003366;"
                                                    class="h-8 w-8 rounded-full flex items-center justify-center text-white">
                                                    <span class="material-icons text-sm">person</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        @if($loan->user)
                                                            {{ $loan->user->name }}
                                                        @else
                                                            {{ $loan->borrower_name ?? 'N/A' }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($loan->items->count() == 1)
                                                    @php $item = $loan->items->first(); @endphp
                                                    @if($item->loanable)
                                                        {{ class_basename($item->loanable_type) }}: {{ $item->loanable->name }}
                                                    @else
                                                        {{ $item->loanable_type }}
                                                    @endif
                                                @else
                                                    {{ $loan->items->count() }} elementos
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loan->loaned_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($loan->returned_at)
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Devuelto
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Prestado
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        No hay préstamos registrados.
                    </div>
                @endif
            </div>

            <!-- Accesos directos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('inventory.loans.create') }}" style="background-color: #003366; color: white;"
                    class="rounded-xl shadow-lg p-5 flex items-center justify-center gap-3 transition-all transform hover:scale-105 hover:bg-[#002244]">
                    <span class="material-icons text-2xl">add_assign</span>
                    <span class="font-semibold">Nuevo préstamo</span>
                </a>
                <a href="{{ route('inventory.tools.create') }}" style="background-color: #003366; color: white;"
                    class="rounded-xl shadow-lg p-5 flex items-center justify-center gap-3 transition-all transform hover:scale-105 hover:bg-[#002244]">
                    <span class="material-icons text-2xl">add</span>
                    <span class="font-semibold">Nueva herramienta</span>
                </a>
                <a href="{{ route('inventory.kits.create') }}" style="background-color: #003366; color: white;"
                    class="rounded-xl shadow-lg p-5 flex items-center justify-center gap-3 transition-all transform hover:scale-105 hover:bg-[#002244]">
                    <span class="material-icons text-2xl">add_box</span>
                    <span class="font-semibold">Nuevo kit</span>
                </a>
                <a href="{{ route('logs.index') }}" style="background-color: #003366; color: white;"
                    class="rounded-xl shadow-lg p-5 flex items-center justify-center gap-3 transition-all transform hover:scale-105 hover:bg-[#002244]">
                    <span class="material-icons text-2xl">history</span>
                    <span class="font-semibold">Historial</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>