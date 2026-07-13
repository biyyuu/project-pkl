<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kabid = User::updateOrCreate(
            ['email' => 'kabid@example.com'],
            [
                'name' => 'Bapak Kabid',
                'password' => Hash::make('password')
            ]
        );
        $kabid->assignRole('kabid');

        $kasub = User::updateOrCreate(
            ['email' => 'kasub@example.com'],
            [
                'name' => 'Bapak Kasub (Approval)',
                'password' => Hash::make('password')
            ]
        );
        $kasub->assignRole('kasub');
    }
}
