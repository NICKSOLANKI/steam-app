<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prepare user data with only columns that exist
        $userData = [
            'name' => 'Nikhil Solanki',
            'password' => \Illuminate\Support\Facades\Hash::make('NIKHIL1077'),
            'email_verified_at' => now()
        ];

        // Only add role column if it exists in the users table
        if (Schema::hasColumn('users', 'role')) {
            $userData['role'] = 'admin';
        }

        // Create or update the admin user
        User::updateOrCreate(
            ['email' => 'dhavalsolanki615@gmail.com'],
            $userData
        );

        $this->command->info('Admin user seeded successfully.');
    }
}