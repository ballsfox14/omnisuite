<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">location_on</span>
            {{ __('Zonas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">list</span>
                            Listado de Zonas
                        </h3>
                        @can('crear zonas')
                            <a href="{{ route('zones.create') }}"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <span class="material-icons">add</span> Nueva Zona
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
                    @if(session('error'))
                        <div
                            class="mb-4 flex items-center gap-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <span class="material-icons">error</span>
                            {{ session('error') }}
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
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>

                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($zones as $zone)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $zone->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $zone->name }}
                                        </td>
                                        <td class="px-6 py-4">{{ $zone->description ?: '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('zones.show', $zone) }}" style="color: #007bff;"
                                                    title="Ver">
                                                    <span class="material-icons">visibility</span>
                                                </a>
                                                @can('editar zonas')
                                                    <a href="{{ route('zones.edit', $zone) }}" style="color: #ffc107;"
                                                        title="Editar">
                                                        <span class="material-icons">edit</span>
                                                    </a>
                                                @endcan
                                                @can('eliminar zonas')
                                                    <form action="{{ route('zones.destroy', $zone) }}" method="POST"
                                                        class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            style="color: #dc3545; background: none; border: none; cursor: pointer;"
                                                            onclick="return confirm('¿Eliminar zona?')" title="Eliminar">
                                                            <span class="material-icons">delete</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty

                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        <span class="material-icons align-middle mr-1">info</span> No hay zonas
                                        registradas.
                                    </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $zones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>