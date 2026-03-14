<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PermisosRolesSeeder::class);
    }
}