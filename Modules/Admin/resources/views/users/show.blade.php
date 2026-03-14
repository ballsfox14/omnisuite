<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">visibility</span>
            {{ __('Detalle de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Encabezado con botones de acción -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold flex items-center gap-2">
                            <span class="material-icons" style="color: #003366;">person</span>
                            {{ $user->name }}
                        </h3>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               style="background-color: #ffc107; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">edit</span> Editar
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-icons">arrow_back</span> Volver
                            </a>
                        </div>
                    </div>

                    <!-- Información básica del usuario en cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">tag</span> ID
                            </p>
                            <p class="mt-1 text-lg">{{ $user->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">badge</span> Nombre
                            </p>
                            <p class="mt-1 text-lg">{{ $user->name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">email</span> Email
                            </p>
                            <p class="mt-1 text-lg">{{ $user->email }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
                                <span class="material-icons text-gray-400">calendar_today</span> Fecha de registro
                            </p>
                            <p class="mt-1 text-lg">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Roles asignados (card) -->
                    @if($user->roles->count() > 0)
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-1">
                            <span class="material-icons" style="color: #003366;">assignment_ind</span>
                            Roles Asignados
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 flex items-center gap-1">
                                    <span class="material-icons text-green-600" style="font-size: 16px;">check_circle</span>
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Permisos directos agrupados por categoría -->
                    @if($user->permissions->count() > 0)
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-1">
                            <span class="material-icons" style="color: #003366;">security</span>
                            Permisos Directos
                        </h4>

                        @php
                            // Agrupar permisos por la segunda palabra (categoría)
                            $grouped = $user->permissions->groupBy(function($perm) {
                                $parts = explode(' ', $perm->name);
                                return $parts[1] ?? 'otros';
                            });
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($grouped as $category => $perms)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <h5 class="font-medium text-gray-700 mb-2 flex items-center gap-1 capitalize">
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
                                    </h5>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($perms as $perm)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Mensaje si no tiene permisos directos -->
                    @if($user->permissions->count() == 0 && $user->roles->count() == 0)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center text-gray-500">
                            <span class="material-icons align-middle mr-1">info</span>
                            Este usuario no tiene roles ni permisos asignados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>