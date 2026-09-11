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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Optional title for banner
            $table->text('description')->nullable(); // Optional description
            $table->string('image_path'); // Banner image
            $table->string('link')->nullable(); // Optional link when clicked
            $table->integer('order')->default(0); // Order of display
            $table->boolean('is_active')->default(true); // Active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
