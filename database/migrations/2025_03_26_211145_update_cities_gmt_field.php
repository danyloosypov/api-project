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
        Schema::table('cities', function (Blueprint $table) {
            // Modify the 'gmt' column from integer to decimal (4, 2)
            $table->string('gmt')->nullable()->change();
            $table->string('timezone')->nullable()->change();
            $table->string('latitude')->nullable()->change();
            $table->string('longitude')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            // Revert the 'gmt' column back to integer
            $table->string('gmt')->change();
            $table->string('timezone')->change();
            $table->string('latitude')->change();
            $table->string('longitude')->change();
        });
    }
};
