<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->date('release_date')->nullable()->after('developer');
            $table->string('trailer_url')->nullable()->after('release_date');
            $table->string('tags')->nullable()->after('trailer_url');
            $table->boolean('supports_windows')->default(true)->after('tags');
            $table->boolean('supports_controller')->default(true)->after('supports_windows');
            $table->boolean('is_single_player')->default(true)->after('supports_controller');
            $table->text('min_requirements')->nullable()->after('is_single_player');
            $table->text('rec_requirements')->nullable()->after('min_requirements');
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


