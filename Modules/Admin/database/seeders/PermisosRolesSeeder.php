<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosRolesSeeder extends Seeder
{
    public function run()
    {
        \Log::info('Ejecutando PermisosRolesSeeder');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',
            'ver permisos',
            'crear permisos',
            'editar permisos',
            'eliminar permisos',
            'ver herramientas',
            'crear herramientas',
            'editar herramientas',
            'eliminar herramientas',
            'ver kits',
            'crear kits',
            'editar kits',
            'eliminar kits',
            'ver prestamos',
            'crear prestamos',
            'devolver prestamos',
            'ver areas',
            'crear areas',
            'editar areas',
            'eliminar areas',
            'ver reportes',
            'ver logs',
            'ver asistencia',
            'marcar asistencia',
            'cerrar semanas',
            'usar modo extraordinario',
            'usar modo pausa',
            // Contratos
            'ver contratos',
            'crear contratos',
            'editar contratos',
            'eliminar contratos',

            // Paquetes
            'ver paquetes',
            'crear paquetes',
            'editar paquetes',
            'eliminar paquetes',

            // Zonas
            'ver zonas',
            'crear zonas',
            'editar zonas',
            'eliminar zonas',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $adminRole->givePermissionTo($permisos);
    }
}