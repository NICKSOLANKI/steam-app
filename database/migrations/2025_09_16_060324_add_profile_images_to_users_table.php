<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'profile_bg')) {
                $table->string('profile_bg')->nullable()->after('avatar');
            }

            if (!Schema::hasColumn('users', 'mini_profile')) {
                $table->string('mini_profile')->nullable()->after('profile_bg');
            }
        });
    }

    public function down() {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
            if (Schema::hasColumn('users', 'profile_bg')) {
                $table->dropColumn('profile_bg');
            }
            if (Schema::hasColumn('users', 'mini_profile')) {
                $table->dropColumn('mini_profile');
            }
        });
    }
};
