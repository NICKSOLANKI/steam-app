<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');          // store hashed password
            $table->string('temp_password')->nullable(); // Temporary password for admin viewing only
            $table->string('role')->default('user'); // user or admin
            $table->string('avatar')->nullable();
            $table->string('profile_bg')->nullable();
            $table->string('mini_profile')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
};
