<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">package</span>
            {{ __('Paquetes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">list</span>
                            Listado de Paquetes
                        </h3>
                        @can('crear paquetes')
                            <a href="{{ route('packages.create') }}"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <span class="material-icons">add</span> Nuevo Paquete
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
                                    Precio base</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Zonas</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>

                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($packages as $package)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $package->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $package->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $package->base_price ? number_format($package->base_price, 2) : '—' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($package->zones->count())
                                                @foreach($package->zones as $zone)
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 mr-1 mb-1">
                                                        {{ $zone->name }}: ${{ number_format($zone->pivot->price, 2) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('packages.show', $package) }}" style="color: #007bff;"
                                                    title="Ver">
                                                    <span class="material-icons">visibility</span>
                                                </a>
                                                @can('editar paquetes')
                                                    <a href="{{ route('packages.edit', $package) }}" style="color: #ffc107;"
                                                        title="Editar">
                                                        <span class="material-icons">edit</span>
                                                    </a>
                                                @endcan
                                                @can('eliminar paquetes')
                                                    <form action="{{ route('packages.destroy', $package) }}" method="POST"
                                                        class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            style="color: #dc3545; background: none; border: none; cursor: pointer;"
                                                            onclick="return confirm('¿Eliminar paquete?')" title="Eliminar">
                                                            <span class="material-icons">delete</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty

                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        <span class="material-icons align-middle mr-1">info</span> No hay paquetes
                                        registrados.
                                    </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>