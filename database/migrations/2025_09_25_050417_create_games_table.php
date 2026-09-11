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
        Schema::create('games', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Game title
            $table->string('slug')->unique(); // Unique slug for URL
            $table->text('description')->nullable(); // Game description
            $table->decimal('price', 10, 2)->default(0.00); // Selling price
            $table->decimal('original_price', 10, 2)->nullable(); // Original price for discount
            $table->string('genre', 100); // Game genre
            $table->string('developer', 255)->nullable(); // Developer name
            $table->date('release_date')->nullable(); // Release date
            $table->string('image_path')->nullable(); // Image file path
            $table->boolean('is_featured')->default(false); // Featured flag
            $table->boolean('is_active')->default(true); // Active flag
            $table->timestamps(); // created_at and updated_at

            // Optional: Indexes for faster queries
            $table->index('genre');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
