<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoiceSignalsTable extends Migration
{
    public function up()
    {
        Schema::create('voice_signals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('target_user_id')->nullable();
            $table->string('type');
            $table->text('data');
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('community_channels')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('voice_signals');
    }
}
