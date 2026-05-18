<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove price_per_hour from tables
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('price_per_hour');
        });

        // Clear existing rates data
        DB::table('rates')->truncate();

        // Drop and recreate rates table with new structure
        Schema::dropIfExists('rates');
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('time_period'); // 'afternoon' or 'evening'
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('hourly_rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->decimal('price_per_hour', 15, 2)->after('type')->default(0);
        });

        Schema::dropIfExists('rates');
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->integer('price_per_hour');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
};
