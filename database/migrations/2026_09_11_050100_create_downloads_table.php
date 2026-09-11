<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadsTable extends Migration
{
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('library_id');
            $table->string('game_title');
            $table->string('game_image')->nullable();
            $table->decimal('total_size', 15, 2)->default(0);
            $table->decimal('downloaded_size', 15, 2)->default(0);
            $table->decimal('current_speed', 15, 2)->default(0);
            $table->decimal('peak_speed', 15, 2)->default(0);
            $table->string('status')->default('queued');
            $table->integer('progress')->default(0);
            $table->string('install_path')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('downloads');
    }
}
