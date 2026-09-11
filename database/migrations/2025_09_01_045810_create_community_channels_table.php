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
        if (!Schema::hasTable('community_channels')) {
            Schema::create('community_channels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('server_id')->constrained('community_servers')->onDelete('cascade');
                $table->string('name');
                $table->enum('type', ['text', 'voice'])->default('text');
                $table->text('description')->nullable();
                $table->integer('position')->default(0);
                $table->boolean('is_private')->default(false);
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
        Schema::dropIfExists('community_channels');
    }
};
