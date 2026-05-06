<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Muhamad Kosasih',
            'password' => bcrypt('password123'),
        ]);

        $role = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::query()->pluck('name')->all();

        $role->syncPermissions($permissions);
        $user->syncRoles([$role->name]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
