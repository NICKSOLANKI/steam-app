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
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'plan')) {
                $table->string('plan')->default('monthly');
            }
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('subscriptions', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'price')) {
                $table->integer('price')->default(299);
            }
            if (!Schema::hasColumn('subscriptions', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'end_date')) {
                $table->date('end_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'plan')) {
                $table->dropColumn('plan');
            }
            if (Schema::hasColumn('subscriptions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('subscriptions', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('subscriptions', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('subscriptions', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('subscriptions', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};
