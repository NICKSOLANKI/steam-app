<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the admin user
        User::updateOrCreate(
            ['email' => 'dhavalsolanki615@gmail.com'],
            [
                'name' => 'Nikhil Solanki',
                'password' => \Illuminate\Support\Facades\Hash::make('NIKHIL1077'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );

        $this->command->info('Admin user seeded successfully.');
    }
}