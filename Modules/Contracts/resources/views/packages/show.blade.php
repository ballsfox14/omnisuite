<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">visibility</span>
            {{ __('Detalle del Paquete') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">package</span>
                            {{ $package->name }}
                        </h3>
                        <div class="flex gap-2">
                            @can('editar paquetes')
                                <a href="{{ route('packages.edit', $package) }}"
                                    style="background-color: #ffc107; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <span class="material-icons">edit</span> Editar
                                </a>
                            @endcan
                            <a href="{{ route('packages.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">arrow_back</span> Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">tag</span> ID
                            </p>
                            <p class="mt-1 text-lg">{{ $package->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">badge</span> Nombre
                            </p>
                            <p class="mt-1 text-lg">{{ $package->name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">attach_money</span> Precio base
                            </p>
                            <p class="mt-1 text-lg">
                                {{ $package->base_price ? number_format($package->base_price, 2) : '—' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">calendar_today</span> Fecha de creación
                            </p>
                            <p class="mt-1 text-lg">{{ $package->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">description</span> Descripción
                            </p>
                            <p class="mt-1">{{ $package->description ?: 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <h4 class="text-md font-semibold mt-4 mb-2 flex items-center gap-2">
                        <span class="material-icons" style="color: #003366;">location_on</span>
                        Precios por zona
                    </h4>
                    @if($package->zones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">

                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Zona</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                    比
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($package->zones as $zone)

                                        <td class="px-4 py-2">{{ $zone->name }}</td>
                                        <td class="px-4 py-2">{{ $zone->description ?: '—' }}</td>
                                        <td class="px-4 py-2">${{ number_format($zone->pivot->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No hay precios definidos para este paquete.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>