<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'adminpusdatin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('pusdatin123'),
            ]
        );
        $admin->assignRole('admin');

        $kasub = User::updateOrCreate(
            ['email' => 'kasub@gmail.com'],
            [
                'name' => 'Kasub',
                'password' => Hash::make('pusdatin123'),
            ]
        );
        $kasub->assignRole('kasub');

        $kabid = User::updateOrCreate(
            ['email' => 'kabid@gmail.com'],
            [
                'name' => 'Kabid',
                'password' => Hash::make('pusdatin123'),
            ]
        );
        $kabid->assignRole('kabid');
    }
}