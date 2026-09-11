<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('voice_sessions')) {
            Schema::create('voice_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('channel_id')->constrained('community_channels')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('session_id')->unique();
                $table->boolean('is_muted')->default(false);
                $table->boolean('is_deafened')->default(false);
                $table->timestamp('joined_at');
                $table->timestamp('left_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voice_sessions');
    }
};
