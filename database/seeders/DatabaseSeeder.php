<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Essential System Seeds (Production & Local)
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class, 
        ]);
        
        // 2. Create Super Admin (Production & Local)
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('super_admin');

        // 3. Dummy Data (Local/Testing Only)
        if (app()->environment('local', 'testing')) {
            $this->call(DummyDataSeeder::class);
        }
    }
}
