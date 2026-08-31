<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'adminpusdatin@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'adminpusdatin',
                'password' => Hash::make('pusdatin123'),
                'recovery_email' => 'adminpusdatin@gmail.com',
            ]
        );
        $admin->assignRole('admin');

        $kasub = User::updateOrCreate(
            ['email' => 'kasub@gmail.com'],
            [
                'name' => 'Kasub',
                'username' => 'kasub',
                'password' => Hash::make('pusdatin123'),
                'recovery_email' => 'kasub@gmail.com',
            ]
        );
        $kasub->assignRole('kasub');

        $kabid = User::updateOrCreate(
            ['email' => 'kabid@gmail.com'],
            [
                'name' => 'Kabid',
                'username' => 'kabid',
                'password' => Hash::make('pusdatin123'),
                'recovery_email' => 'kabid@gmail.com',
            ]
        );
        $kabid->assignRole('kabid');
    }
}
