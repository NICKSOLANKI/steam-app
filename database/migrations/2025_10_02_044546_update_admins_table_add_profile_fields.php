<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'avatar')) {
                $table->string('avatar')->nullable()->after('password');
            }
            if (!Schema::hasColumn('admins', 'phone')) {
                $table->string('phone')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('admins', 'bio')) {
                $table->text('bio')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('admins', 'role')) {
                $table->string('role')->default('admin')->after('bio');
            }
            if (!Schema::hasColumn('admins', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('admins', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
        });

        // Update existing admin to super_admin role
        \DB::table('admins')->where('email', 'dhaval@gmail.com')->update([
            'role' => 'super_admin',
            'is_active' => true,
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'bio', 'role', 'is_active', 'last_login_at']);
        });
    }
};