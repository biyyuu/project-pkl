<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kabidRole = Role::firstOrCreate(['name' => 'kabid']);
        $kasubRole = Role::firstOrCreate(['name' => 'kasub']);

        // Migrate existing users to spatie roles based on their 'role' string column
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole($adminRole);
            } elseif ($user->role === 'kabid') {
                $user->assignRole($kabidRole);
            } elseif ($user->role === 'kasub') {
                $user->assignRole($kasubRole);
            } else {
                // Default to admin if existing user had no matches, or create a 'user' role.
                // Assuming defaults for this app
                if ($user->email == 'adminpusdatin@gmail.com') {
                    $user->assignRole($adminRole);
                }
            }
        }
    }
}
