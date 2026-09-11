<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Update existing admin credentials
        \DB::table('admins')->where('email', 'dhaval@gmail.com')->update([
            'name' => 'Admin Dhaval',
            'email' => 'dhavalsolanki615@gmail.com',
            'password' => Hash::make('NIKHIL1077'),
            'role' => 'super_admin',
            'is_active' => true,
            'updated_at' => now()
        ]);

        // If no admin exists with old email, create new one
        $adminExists = \DB::table('admins')->where('email', 'dhavalsolanki615@gmail.com')->exists();
        
        if (!$adminExists) {
            \DB::table('admins')->insert([
                'name' => 'Admin Dhaval',
                'email' => 'dhavalsolanki615@gmail.com',
                'password' => Hash::make('NIKHIL1077'),
                'role' => 'super_admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        // Revert back to old credentials
        \DB::table('admins')->where('email', 'dhavalsolanki615@gmail.com')->update([
            'name' => 'Admin',
            'email' => 'dhaval@gmail.com',
            'password' => Hash::make('nick'),
            'updated_at' => now()
        ]);
    }
};