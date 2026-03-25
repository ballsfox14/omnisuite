<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <span class="material-icons mr-1 text-base align-middle">dashboard</span>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Administración Dropdown -->
                    @php
                        $showAdminDropdown = auth()->user()->can('ver usuarios') ||
                            auth()->user()->can('ver roles') ||
                            auth()->user()->can('ver permisos') ||
                            auth()->user()->can('ver areas');
                    @endphp
                    @if($showAdminDropdown)
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <span class="material-icons mr-1 text-base">admin_panel_settings</span>
                                        <div>Administración</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('ver usuarios')
                                        <x-dropdown-link :href="route('admin.users.index')">
                                            <span class="material-icons mr-2 text-base">people</span>
                                            Usuarios
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver roles')
                                        <x-dropdown-link :href="route('admin.roles.index')">
                                            <span class="material-icons mr-2 text-base">security</span>
                                            Roles
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver permisos')
                                        <x-dropdown-link :href="route('admin.permissions.index')">
                                            <span class="material-icons mr-2 text-base">lock_open</span>
                                            Permisos
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver areas')
                                        <x-dropdown-link :href="route('admin.areas.index')">
                                            <span class="material-icons mr-2 text-base">business</span>
                                            Áreas
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Inventario Dropdown (sin cambios) -->
                    @php
                        $showInventoryDropdown = auth()->user()->can('ver herramientas') ||
                            auth()->user()->can('ver kits') ||
                            auth()->user()->can('ver prestamos');
                    @endphp
                    @if($showInventoryDropdown)
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <span class="material-icons mr-1 text-base">inventory</span>
                                        <div>Inventario</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('ver herramientas')
                                        <x-dropdown-link :href="route('inventory.tools.index')">
                                            <span class="material-icons mr-2 text-base">handyman</span>
                                            Herramientas
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver kits')
                                        <x-dropdown-link :href="route('inventory.kits.index')">
                                            <span class="material-icons mr-2 text-base">backpack</span>
                                            Kits
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver prestamos')
                                        <x-dropdown-link :href="route('inventory.loans.index')">
                                            <span class="material-icons mr-2 text-base">assignment</span>
                                            Préstamos
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Asistencia Dropdown (nuevo) -->
                    @php
                        $showAttendanceDropdown = auth()->user()->can('marcar asistencia') ||
                            auth()->user()->can('ver asistencia');
                    @endphp
                    @if($showAttendanceDropdown)
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <span class="material-icons mr-1 text-base">access_time</span>
                                        <div>Asistencia</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('marcar asistencia')
                                        <x-dropdown-link :href="route('attendance.mark.form')">
                                            <span class="material-icons mr-2 text-base">touch_app</span>
                                            Marcar asistencia
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver asistencia')
                                        <x-dropdown-link :href="route('attendance.index')">
                                            <span class="material-icons mr-2 text-base">list</span>
                                            Historial de asistencia
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver asistencia')
                                        <x-dropdown-link :href="route('attendance.admin.balance')">
                                            <span class="material-icons mr-2 text-base">analytics</span>
                                            Balance de asistencia
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Reportes Dropdown (nuevo) -->
                    @php
                        $showReportsDropdown = auth()->user()->can('ver reportes') ||
                            auth()->user()->can('ver logs');
                    @endphp
                    @if($showReportsDropdown)
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <span class="material-icons mr-1 text-base">assessment</span>
                                        <div>Reportes</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('ver reportes')
                                        <x-dropdown-link :href="route('reports.index')">
                                            <span class="material-icons mr-2 text-base">assessment</span>
                                            Reportes generales
                                        </x-dropdown-link>
                                    @endcan
                                    @can('ver logs')
                                        <x-dropdown-link :href="route('logs.index')">
                                            <span class="material-icons mr-2 text-base">history</span>
                                            Historial de actividades
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (sin cambios) -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <span class="material-icons mr-1 text-base">account_circle</span>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="material-icons mr-2 text-base">edit</span>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <span class="material-icons mr-2 text-base">logout</span>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (versión móvil) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <span class="material-icons mr-2 text-base align-middle">dashboard</span>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Responsive Administración -->
            @php
                $hasAdminPerm = auth()->user()->can('ver usuarios') ||
                    auth()->user()->can('ver roles') ||
                    auth()->user()->can('ver permisos') ||
                    auth()->user()->can('ver areas');
            @endphp
            @if($hasAdminPerm)
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            <span class="material-icons mr-2 text-base align-middle">admin_panel_settings</span>
                            Administración
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @can('ver usuarios')
                            <x-responsive-nav-link :href="route('admin.users.index')">
                                <span class="material-icons mr-2 text-base">people</span>
                                Usuarios
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver roles')
                            <x-responsive-nav-link :href="route('admin.roles.index')">
                                <span class="material-icons mr-2 text-base">security</span>
                                Roles
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver permisos')
                            <x-responsive-nav-link :href="route('admin.permissions.index')">
                                <span class="material-icons mr-2 text-base">lock_open</span>
                                Permisos
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver areas')
                            <x-responsive-nav-link :href="route('admin.areas.index')">
                                <span class="material-icons mr-2 text-base">business</span>
                                Áreas
                            </x-responsive-nav-link>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Responsive Inventario -->
            @php
                $hasInventoryPerm = auth()->user()->can('ver herramientas') ||
                    auth()->user()->can('ver kits') ||
                    auth()->user()->can('ver prestamos');
            @endphp
            @if($hasInventoryPerm)
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            <span class="material-icons mr-2 text-base align-middle">inventory</span>
                            Inventario
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @can('ver herramientas')
                            <x-responsive-nav-link :href="route('inventory.tools.index')">
                                <span class="material-icons mr-2 text-base">handyman</span>
                                Herramientas
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver kits')
                            <x-responsive-nav-link :href="route('inventory.kits.index')">
                                <span class="material-icons mr-2 text-base">backpack</span>
                                Kits
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver prestamos')
                            <x-responsive-nav-link :href="route('inventory.loans.index')">
                                <span class="material-icons mr-2 text-base">assignment</span>
                                Préstamos
                            </x-responsive-nav-link>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Responsive Asistencia -->
            @php
                $hasAttendancePerm = auth()->user()->can('marcar asistencia') ||
                    auth()->user()->can('ver asistencia');
            @endphp
            @if($hasAttendancePerm)
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            <span class="material-icons mr-2 text-base align-middle">access_time</span>
                            Asistencia
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @can('marcar asistencia')
                            <x-responsive-nav-link :href="route('attendance.mark.form')">
                                <span class="material-icons mr-2 text-base">touch_app</span>
                                Marcar asistencia
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver asistencia')
                            <x-responsive-nav-link :href="route('attendance.index')">
                                <span class="material-icons mr-2 text-base">list</span>
                                Historial de asistencia
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver asistencia')
                            <x-responsive-nav-link :href="route('attendance.admin.balance')">
                                <span class="material-icons mr-2 text-base">analytics</span>
                                Balance de asistencia
                            </x-responsive-nav-link>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Responsive Reportes -->
            @php
                $hasReportsPerm = auth()->user()->can('ver reportes') ||
                    auth()->user()->can('ver logs');
            @endphp
            @if($hasReportsPerm)
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            <span class="material-icons mr-2 text-base align-middle">assessment</span>
                            Reportes
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @can('ver reportes')
                            <x-responsive-nav-link :href="route('reports.index')">
                                <span class="material-icons mr-2 text-base">assessment</span>
                                Reportes generales
                            </x-responsive-nav-link>
                        @endcan
                        @can('ver logs')
                            <x-responsive-nav-link :href="route('logs.index')">
                                <span class="material-icons mr-2 text-base">history</span>
                                Historial de actividades
                            </x-responsive-nav-link>
                        @endcan
                    </div>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options (sin cambios) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    <span class="material-icons mr-2 text-base align-middle">account_circle</span>
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="material-icons mr-2 text-base">edit</span>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <span class="material-icons mr-2 text-base">logout</span>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>