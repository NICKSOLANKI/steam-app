<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banner_sliders', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['game_id']);
            
            // Make game_id nullable
            $table->unsignedBigInteger('game_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable support
            $table->foreign('game_id')->references('id')->on('games')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_sliders', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['game_id']);
            
            // Make game_id required again
            $table->unsignedBigInteger('game_id')->nullable(false)->change();
            
            // Re-add the original foreign key constraint
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }
};