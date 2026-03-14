<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">edit</span>
            {{ __('Editar Área') }}: {{ $area->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.areas.update', $area) }}">
                        @csrf @method('PUT')

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">badge</span> Nombre del área
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $area->name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('name') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><span
                                    class="material-icons text-sm">error</span>{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">description</span> Descripción
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $area->description) }}</textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                    <span class="material-icons text-sm">error</span>{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.areas.index') }}"
                                style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Actualizar Área
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>