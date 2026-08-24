<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kabidRole = Role::firstOrCreate(['name' => 'kabid']);
        $kasubRole = Role::firstOrCreate(['name' => 'kasub']);


        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole($adminRole);
            } elseif ($user->role === 'kabid') {
                $user->assignRole($kabidRole);
            } elseif ($user->role === 'kasub') {
                $user->assignRole($kasubRole);
            } else {
                if ($user->email == 'adminpusdatin@gmail.com') {
                    $user->assignRole($adminRole);
                }
            }
        }
    }
}
