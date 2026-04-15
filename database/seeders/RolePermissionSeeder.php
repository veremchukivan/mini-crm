<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionNames = [
            'tickets.view',
            'tickets.update',
        ];

        $permissions = collect($permissionNames)
            ->map(fn (string $permissionName) => Permission::findOrCreate($permissionName, 'web'));

        $managerRole = Role::findOrCreate('manager', 'web');
        $adminRole = Role::findOrCreate('admin', 'web');

        $managerRole->syncPermissions($permissions);
        $adminRole->syncPermissions($permissions);
    }
}
