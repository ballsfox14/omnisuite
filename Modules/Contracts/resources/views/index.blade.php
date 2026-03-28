<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">description</span>
            {{ __('Contratos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">list</span>
                            Listado de Contratos
                        </h3>
                        @can('crear contratos')
                            <a href="{{ route('contracts.create') }}"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <span class="material-icons">add</span> Nuevo Contrato
                            </a>
                        @endcan
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
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Creado por</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($contracts as $contract)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contract->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contract->client_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contract->creator->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $contract->status_color }}-100 text-{{ $contract->status_color }}-800">
                                                {{ $contract->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contract->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('contracts.show', $contract) }}" style="color: #007bff;"
                                                    title="Ver">
                                                    <span class="material-icons">visibility</span>
                                                </a>
                                                @can('editar contratos')
                                                    <a href="{{ route('contracts.edit', $contract) }}" style="color: #ffc107;"
                                                        title="Editar">
                                                        <span class="material-icons">edit</span>
                                                    </a>
                                                @endcan
                                                @can('eliminar contratos')
                                                    <form action="{{ route('contracts.destroy', $contract) }}" method="POST"
                                                        class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            style="color: #dc3545; background: none; border: none; cursor: pointer;"
                                                            onclick="return confirm('¿Eliminar contrato?')" title="Eliminar">
                                                            <span class="material-icons">delete</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <span class="material-icons align-middle mr-1">info</span> No hay contratos
                                        registrados.
                                    </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>