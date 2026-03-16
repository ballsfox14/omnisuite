<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">build</span>
            {{ __('Herramientas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">list</span>
                            Listado de Herramientas
                        </h3>
                        <div class="flex gap-2">
                            <a href="{{ route('inventory.tools.pdf-codes') }}"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <span class="material-icons">picture_as_pdf</span>
                                Exportar códigos PDF
                            </a>
                            <a href="{{ route('inventory.tools.create') }}"
                                style="background-color: #003366; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                <span class="material-icons">add</span> Nueva Herramienta
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div
                            class="mb-4 flex items-center gap-2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <span class="material-icons">check_circle</span>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="mb-4 flex items-center gap-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <span class="material-icons">error</span>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">tag</span> ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">badge</span> Nombre
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">category</span> Tipo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">trademark</span> Marca
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">model_training</span> Modelo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">qr_code</span> Código
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="material-icons align-middle mr-1"
                                            style="font-size: 16px;">settings</span> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tools as $tool)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tool->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tool->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tool->tipo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tool->marca }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tool->modelo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $tool->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('inventory.tools.show', $tool) }}" style="color: #007bff;"
                                                    title="Ver">
                                                    <span class="material-icons">visibility</span>
                                                </a>
                                                <a href="{{ route('inventory.tools.edit', $tool) }}" style="color: #ffc107;"
                                                    title="Editar">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <form action="{{ route('inventory.tools.destroy', $tool) }}" method="POST"
                                                    class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        style="color: #dc3545; background: none; border: none; cursor: pointer;"
                                                        onclick="return confirm('¿Eliminar esta herramienta?')"
                                                        title="Eliminar">
                                                        <span class="material-icons">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            <span class="material-icons align-middle mr-1">info</span> No hay herramientas
                                            registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tools->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>