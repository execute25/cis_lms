<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'secretary']);
        Role::create(['name' => 'team-leader']);
        Role::create(['name' => 'cell-leader']);
        Role::create(['name' => 'normal']);
    }
}
