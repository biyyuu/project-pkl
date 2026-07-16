<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
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

        // Create roles and assign permissions
        // Admin — full access
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        // Kasub — view all + approval + create outgoings
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

        // Kabid — view only
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
