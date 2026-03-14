<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kits') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Listado de Kits</h3>
                        <!-- Botón Nuevo Kit con color sólido #003366 -->
                        <a href="{{ route('inventory.kits.create') }}"
                            style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                            <span class="material-icons">add</span> Nuevo Kit
                        </a>
                    </div>

                    @if (session('success'))
                        <div
                            style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <span class="material-icons">check_circle</span> {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-icons"
                                        style="font-size: 16px; vertical-align: middle;">tag</span> ID
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-icons"
                                        style="font-size: 16px; vertical-align: middle;">badge</span> Nombre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-icons"
                                        style="font-size: 16px; vertical-align: middle;">qr_code</span> Código
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-icons"
                                        style="font-size: 16px; vertical-align: middle;">build</span> Herramientas
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="material-icons"
                                        style="font-size: 16px; vertical-align: middle;">settings</span> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($kits as $kit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $kit->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $kit->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $kit->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $kit->tools->count() }} herramientas</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                        <a href="{{ route('inventory.kits.show', $kit) }}" style="color: #007bff;"
                                            title="Ver">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="{{ route('inventory.kits.edit', $kit) }}" style="color: #ffc107;"
                                            title="Editar">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <form action="{{ route('inventory.kits.destroy', $kit) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="color: #dc3545; background: none; border: none; cursor: pointer;"
                                                onclick="return confirm('¿Eliminar kit?')" title="Eliminar">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        <span class="material-icons" style="vertical-align: middle;">info</span> No hay kits
                                        registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $kits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>