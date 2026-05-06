<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = ['audit-logs', 'permissions.index', 'permissions.create', 'permissions.edit', 'permissions.delete', 'users.index', 'users.create', 'users.edit', 'users.delete', 'products.index', 'products.create', 'products.edit', 'products.delete', 'role.index', 'role.create', 'role.edit', 'role.delete', 'setting-apps'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
