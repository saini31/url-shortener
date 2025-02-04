<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],  // Prevent duplicates
            [
                'name' => 'Super Admin',
                
                'password' => Hash::make('password'),
                'role' => 'super_admin', // Ensure consistency
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],  // Prevent duplicates
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin', // Assign role as 'admin'
            ]
        );
    }
}
