<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">person_add</span>
            {{ __('Crear Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Datos básicos en tarjeta -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">info</span>
                                Datos del Usuario
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">badge</span> Nombre
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('name') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><span class="material-icons text-sm">error</span>{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">email</span> Email
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('email') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><span class="material-icons text-sm">error</span>{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">lock</span> Contraseña
                                    </label>
                                    <input type="password" name="password" id="password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('password') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><span class="material-icons text-sm">error</span>{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">lock_reset</span> Confirmar Contraseña
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Área (nuevo campo) -->
                                <div class="md:col-span-2">
                                    <label for="area_id" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">business</span> Área
                                    </label>
                                    <select name="area_id" id="area_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccionar área</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Permisos agrupados por categorías (igual que antes) -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">security</span>
                                Permisos del Usuario
                            </h3>

                            @php
                                $grouped = $permissions->groupBy(function($perm) {
                                    $parts = explode(' ', $perm->name);
                                    return $parts[1] ?? 'otros';
                                });
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($grouped as $category => $perms)
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-1 capitalize">
                                            <span class="material-icons" style="color: #003366;">
                                                @switch($category)
                                                    @case('usuarios') people @break
                                                    @case('roles') admin_panel_settings @break
                                                    @case('permisos') lock @break
                                                    @case('herramientas') build @break
                                                    @case('kits') inventory @break
                                                    @default check_circle
                                                @endswitch
                                            </span>
                                            {{ $category }}
                                        </h4>
                                        <div class="space-y-2">
                                            @foreach($perms as $perm)
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                           id="perm_{{ $perm->id }}"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label for="perm_{{ $perm->id }}" class="ml-2 text-sm text-gray-700">
                                                        {{ $perm->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-1">
                                <span class="material-icons" style="color: #003366;">assignment_ind</span>
                                Asignar Roles
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                               id="role_{{ $role->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                        <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.index') }}"
                               style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">cancel</span> Cancelar
                            </a>
                            <button type="submit"
                                    style="background-color: #28a745; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; border: none; cursor: pointer;">
                                <span class="material-icons">save</span> Guardar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>