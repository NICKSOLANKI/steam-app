<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            // Drop any old/unneeded columns
            if (Schema::hasColumn('games', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('games', 'old_price')) {
                $table->dropColumn('old_price');
            }

            // Add missing columns or fix their defaults
            if (!Schema::hasColumn('games', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('games', 'original_price')) {
                $table->decimal('original_price', 10, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('games', 'image_path')) {
                $table->string('image_path')->nullable()->after('developer');
            }
            if (!Schema::hasColumn('games', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('image_path');
            }
            if (!Schema::hasColumn('games', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            // Optional: reverse changes
            if (Schema::hasColumn('games', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('games', 'original_price')) {
                $table->dropColumn('original_price');
            }
            if (Schema::hasColumn('games', 'image_path')) {
                $table->dropColumn('image_path');
            }
            if (Schema::hasColumn('games', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('games', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};
