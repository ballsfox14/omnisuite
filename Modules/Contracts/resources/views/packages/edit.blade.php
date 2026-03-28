<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">edit</span>
            {{ __('Editar Paquete') }}: {{ $package->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('packages.update', $package) }}">
                        @csrf @method('PUT')

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">badge</span> Nombre
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="base_price"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">attach_money</span> Precio base
                                    </label>
                                    <input type="number" step="0.01" name="base_price" id="base_price"
                                        value="{{ old('base_price', $package->base_price) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('base_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">description</span> Descripción
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $package->description) }}</textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">location_on</span>
                                Precios por zona
                            </h3>
                            <div id="zones-container">
                                @foreach($zones as $zone)
                                    @php
                                        $pivot = $package->zones->find($zone->id);
                                        $price = $pivot ? $pivot->pivot->price : null;
                                    @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 zone-item"
                                        data-zone-id="{{ $zone->id }}">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ $zone->name }}</label>
                                            <input type="text"
                                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100"
                                                value="{{ $zone->description }}" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Precio</label>
                                            <input type="number" step="0.01" name="zones[{{ $zone->id }}][price]"
                                                value="{{ old("zones.{$zone->id}.price", $price) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10"
                                                placeholder="Precio para esta zona">
                                            <input type="hidden" name="zones[{{ $zone->id }}][id]" value="{{ $zone->id }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('packages.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>