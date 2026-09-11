<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            if (!Schema::hasColumn('games', 'release_date')) {
                $table->date('release_date')->nullable()->after('developer');
            }
            if (!Schema::hasColumn('games', 'trailer_url')) {
                $table->string('trailer_url')->nullable()->after('release_date');
            }
            if (!Schema::hasColumn('games', 'tags')) {
                $table->string('tags')->nullable()->after('trailer_url');
            }
            if (!Schema::hasColumn('games', 'supports_windows')) {
                $table->boolean('supports_windows')->default(true)->after('tags');
            }
            if (!Schema::hasColumn('games', 'supports_controller')) {
                $table->boolean('supports_controller')->default(true)->after('supports_windows');
            }
            if (!Schema::hasColumn('games', 'is_single_player')) {
                $table->boolean('is_single_player')->default(true)->after('supports_controller');
            }
            if (!Schema::hasColumn('games', 'min_requirements')) {
                $table->text('min_requirements')->nullable()->after('is_single_player');
            }
            if (!Schema::hasColumn('games', 'rec_requirements')) {
                $table->text('rec_requirements')->nullable()->after('min_requirements');
            }
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn([
                'release_date',
                'trailer_url',
                'tags',
                'supports_windows',
                'supports_controller',
                'is_single_player',
                'min_requirements',
                'rec_requirements',
            ]);
        });
    }
};


