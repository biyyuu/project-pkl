<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'view-dashboard',
            'view-items',
            'create-items',
            'update-items',
            'delete-items',
            'view-outgoings',
            'create-outgoings',
            'update-outgoings',
            'delete-outgoings',
            'view-approval',
            'approve-outgoings',
            'reject-outgoings',
            'view-history',
            'export-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        $kasub = Role::firstOrCreate(['name' => 'kasub', 'guard_name' => 'web']);
        $kasub->syncPermissions([
            'view-dashboard',
            'view-items',
            'view-outgoings',
            'create-outgoings',
            'view-approval',
            'approve-outgoings',
            'reject-outgoings',
            'view-history',
            'export-reports',
        ]);

        $kabid = Role::firstOrCreate(['name' => 'kabid', 'guard_name' => 'web']);
        $kabid->syncPermissions([
            'view-dashboard',
            'view-items',
            'view-outgoings',
            'view-history',
            'export-reports',
        ]);
    }
}
