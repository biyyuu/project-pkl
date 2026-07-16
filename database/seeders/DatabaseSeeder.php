<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles & permissions must be created first
        $this->call([
            RoleAndPermissionSeeder::class,
            AdminSeeder::class,
            DummyUserSeeder::class,
        ]);
    }
}
