<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CreateBannerSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_sliders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->string('title');
            $table->string('slug');
            $table->string('image_path')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('display_order')->default(1); // 1, 2, 3 for the 3 slider positions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            
            // Ensure only 3 slider positions
            $table->unique('display_order');
        });

        // Seed three default banner slides (copies asset images into storage and inserts rows)
        try {
            $defaults = [
                [
                    'title' => 'Wolverine',
                    'slug' => 'wolverine',
                    'asset_file' => 'counter.jpg',
                    'display_order' => 1,
                    'old_price' => 4599,
                    'new_price' => 2299,
                ],
                [
                    'title' => 'The Last of Us',
                    'slug' => 'the-last-of-us-part-ii',
                    'asset_file' => 'last.jpg',
                    'display_order' => 2,
                    'old_price' => 3999,
                    'new_price' => 1899,
                ],
                [
                    'title' => 'Spider-Man',
                    'slug' => 'spider-man-miles-morales',
                    'asset_file' => 's8ul.jpg',
                    'display_order' => 3,
                    'old_price' => 4199,
                    'new_price' => 1799,
                ],
            ];

            foreach ($defaults as $def) {
                $publicPath = public_path('asset/imagies/' . $def['asset_file']);
                $storedPath = null;
                if (file_exists($publicPath)) {
                    $ext = pathinfo($publicPath, PATHINFO_EXTENSION) ?: 'jpg';
                    $name = uniqid('banner_', true) . '.' . $ext;
                    $storedPath = 'banners/' . $name;
                    $data = @file_get_contents($publicPath);
                    if ($data !== false) {
                        Storage::disk('public')->put($storedPath, $data);
                    } else {
                        $storedPath = null;
                    }
                }

                DB::table('banner_sliders')->updateOrInsert(
                    ['display_order' => $def['display_order']],
                    [
                        'game_id' => null,
                        'title' => $def['title'],
                        'slug' => $def['slug'],
                        'image_path' => $storedPath,
                        'price' => $def['new_price'],
                        'original_price' => $def['old_price'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        } catch (\Throwable $e) {
            // If seeding fails during migration, continue without blocking schema creation
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_sliders');
    }
}
