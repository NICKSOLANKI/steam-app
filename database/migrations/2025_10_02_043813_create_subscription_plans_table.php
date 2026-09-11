<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Monthly Premium", "Lifetime Access"
            $table->string('slug')->unique(); // e.g., "monthly", "lifetime"
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('duration_days')->nullable(); // null for lifetime
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // JSON array of features
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insert default subscription plans
        \DB::table('subscription_plans')->insert([
            [
                'name' => 'Monthly Premium',
                'slug' => 'monthly',
                'description' => 'Access all games for one month',
                'price' => 299.00,
                'duration_days' => 30,
                'is_active' => true,
                'features' => json_encode(['Unlimited game access', 'Premium support', 'Early access to new games']),
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lifetime Access',
                'slug' => 'lifetime',
                'description' => 'Unlimited access to all games forever',
                'price' => 2999.00,
                'duration_days' => null,
                'is_active' => true,
                'features' => json_encode(['Unlimited game access', 'Premium support', 'Early access to new games', 'Exclusive content']),
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
};