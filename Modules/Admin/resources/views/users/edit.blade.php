<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="material-icons" style="color: #003366;">edit</span>
            {{ __('Editar Usuario') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf @method('PUT')

                        <!-- Datos básicos -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">info</span>
                                Datos personales
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">badge</span> Nombre
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">email</span> Email
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="area_id" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">business</span> Área
                                    </label>
                                    <select name="area_id" id="area_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                        <option value="">Seleccionar área</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ old('area_id', $user->area_id) == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">qr_code</span> Código de empleado
                                    </label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-gray-50 text-gray-700 text-sm font-mono">
                                            {{ $user->employee_code }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">No se puede modificar.</p>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">lock</span> Nueva contraseña (dejar en blanco para no cambiar)
                                    </label>
                                    <input type="password" name="password" id="password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">lock_reset</span> Confirmar contraseña
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                </div>
                            </div>
                        </div>

                        <!-- Configuración laboral -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">work</span>
                                Configuración laboral
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contract_type" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">work</span> Tipo de contrato
                                    </label>
                                    <select name="contract_type" id="contract_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                        <option value="full_time" {{ old('contract_type', $user->contract_type) == 'full_time' ? 'selected' : '' }}>Tiempo completo</option>
                                        <option value="part_time" {{ old('contract_type', $user->contract_type) == 'part_time' ? 'selected' : '' }}>Medio tiempo</option>
                                        <option value="custom" {{ old('contract_type', $user->contract_type) == 'custom' ? 'selected' : '' }}>Personalizado</option>
                                    </select>
                                    @error('contract_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="weekly_hours" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">schedule</span> Horas semanales
                                    </label>
                                    <input type="number" step="0.5" name="weekly_hours" id="weekly_hours" value="{{ old('weekly_hours', $user->weekly_hours) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('weekly_hours') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">weekend</span> Día de descanso (opcional)
                                    </label>
                                    @php $restDay = old('rest_day', $user->rest_day); @endphp
                                    <div class="flex flex-wrap gap-3 items-center">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="rest_day" value="" {{ $restDay === null ? 'checked' : '' }} class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">No definido</span>
                                        </label>
                                        @foreach([
                                            ['value' => 0, 'label' => 'Domingo'],
                                            ['value' => 1, 'label' => 'Lunes'],
                                            ['value' => 2, 'label' => 'Martes'],
                                            ['value' => 3, 'label' => 'Miércoles'],
                                            ['value' => 4, 'label' => 'Jueves'],
                                            ['value' => 5, 'label' => 'Viernes'],
                                            ['value' => 6, 'label' => 'Sábado'],
                                        ] as $day)
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="rest_day" value="{{ $day['value'] }}" {{ $restDay == $day['value'] ? 'checked' : '' }} class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">{{ $day['label'] }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('rest_day') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Saldo inicial -->
                                <div class="md:col-span-2">
                                    <label for="initial_balance" class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                                        <span class="material-icons text-gray-500">account_balance</span> Saldo inicial (horas)
                                    </label>
                                    <input type="number" step="0.01" name="initial_balance" id="initial_balance" value="{{ old('initial_balance', $user->initial_balance) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10">
                                    @error('initial_balance') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Permisos y roles -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <span class="material-icons" style="color: #003366;">security</span>
                                Permisos y roles
                            </h3>

                            @php
                                $groupedPermissions = $permissions->groupBy(function($perm) {
                                    $parts = explode(' ', $perm->name);
                                    return $parts[1] ?? 'otros';
                                });
                                $userPerms = $user->permissions->pluck('name')->toArray();
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupedPermissions as $category => $perms)
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-1 capitalize">
                                            <span class="material-icons" style="color: #003366;">
                                                @switch($category)
                                                    @case('usuarios') people @break
                                                    @case('roles') admin_panel_settings @break
                                                    @case('permisos') lock @break
                                                    @case('herramientas') build @break
                                                    @case('kits') inventory @break
                                                    @case('areas') business @break
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
                                                           {{ in_array($perm->name, old('permissions', $userPerms)) ? 'checked' : '' }}>
                                                    <label for="perm_{{ $perm->id }}" class="ml-2 text-sm text-gray-700">
                                                        {{ $perm->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-1">
                                    <span class="material-icons" style="color: #003366;">assignment_ind</span>
                                    Roles asignados
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($roles as $role)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                   id="role_{{ $role->id }}"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-700">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.index') }}"
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

    @push('scripts')
    <script>
        const contractSelect = document.getElementById('contract_type');
        const weeklyInput = document.getElementById('weekly_hours');

        function updateWeeklyHours() {
            const val = contractSelect.value;
            if (val === 'full_time') {
                weeklyInput.value = 44;
            } else if (val === 'part_time') {
                weeklyInput.value = 22;
            } else {
                weeklyInput.value = '';
            }
        }
        contractSelect.addEventListener('change', updateWeeklyHours);
        updateWeeklyHours();
    </script>
    @endpush
</x-app-layout>