<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="material-icons" style="color: #003366;">analytics</span>
                {{ __('Reportes') }}
            </h2>
            <p class="text-sm text-gray-600">Genera informes y exporta datos</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Reportes de Herramientas -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-icons" style="color: #003366;">build</span>
                    Herramientas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="material-icons text-3xl text-green-500">build</span>
                                <span
                                    class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">General</span>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Listado de herramientas</h4>
                            <p class="text-sm text-gray-600 mb-4">Exporta todas las herramientas con sus códigos.</p>
                            <div class="flex gap-2">
                                <a href="{{ route('reports.tools.excel') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm"
                                    style="background-color: #28a745; color: white;">
                                    <span class="material-icons text-sm">download</span> Excel
                                </a>
                                <a href="{{ route('reports.tools.pdf') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm"
                                    style="background-color: #dc3545; color: white;">
                                    <span class="material-icons text-sm">picture_as_pdf</span> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes de Kits -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-icons" style="color: #003366;">inventory</span>
                    Kits
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="material-icons text-3xl text-teal-500">inventory</span>
                                <span
                                    class="text-xs font-semibold text-teal-600 bg-teal-100 px-2 py-1 rounded-full">General</span>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Listado de kits</h4>
                            <p class="text-sm text-gray-600 mb-4">Exporta todos los kits y sus componentes.</p>
                            <div class="flex gap-2">
                                <a href="{{ route('reports.kits.excel') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm"
                                    style="background-color: #28a745; color: white;">
                                    <span class="material-icons text-sm">download</span> Excel
                                </a>
                                <a href="{{ route('reports.kits.pdf') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm"
                                    style="background-color: #dc3545; color: white;">
                                    <span class="material-icons text-sm">picture_as_pdf</span> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes de Préstamos -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-icons" style="color: #003366;">assignment</span>
                    Préstamos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="material-icons text-3xl text-blue-500">list_alt</span>
                                <span
                                    class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded-full">General</span>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Listado de préstamos</h4>
                            <p class="text-sm text-gray-600 mb-4">Exporta todos los préstamos registrados.</p>
                            <div class="flex gap-2">
                                <a href="{{ route('reports.loans.excel') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm"
                                    style="background-color: #28a745; color: white;">
                                    <span class="material-icons text-sm">download</span> Excel
                                </a>
                                <a href="{{ route('reports.loans.pdf') }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm"
                                    style="background-color: #dc3545; color: white;">
                                    <span class="material-icons text-sm">picture_as_pdf</span> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>