<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -----------------------
        // Create user_profiles table
        // -----------------------
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // Link to the user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Editable profile fields
            $table->string('avatar')->nullable();       // avatar image URL
            $table->string('background')->nullable();   // profile background URL
            $table->string('mini_profile')->nullable(); // mini profile image URL

            $table->timestamps();
        });

        // -----------------------
        // Insert default admin
        // -----------------------
        $adminEmail = 'dhaval@gmail.com';
        $adminPassword = 'nick'; // Plain text password

        // Check if admin already exists
        $exists = DB::table('users')->where('email', $adminEmail)->exists();

        if (!$exists) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => $adminEmail,
                'password' => $adminPassword, // Stored as plain text
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');

        // Remove the default admin user on rollback
        DB::table('users')->where('email', 'dhaval@gmail.com')->delete();
    }
};
