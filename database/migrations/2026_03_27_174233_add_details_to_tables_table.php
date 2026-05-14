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
        Schema::table('tables', function (Blueprint $table) {
            $table->decimal('price_per_hour', 15, 2)->after('type')->default(0);
            $table->integer('capacity')->after('price_per_hour')->default(0);
            $table->text('description')->after('capacity')->nullable();
            $table->enum('status', ['active', 'maintenance'])->after('description')->default('active');
            $table->string('image')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn(['price_per_hour', 'capacity', 'description', 'status', 'image']);
        });
    }
};
