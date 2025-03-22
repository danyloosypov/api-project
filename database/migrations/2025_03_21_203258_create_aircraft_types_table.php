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
        Schema::create('aircraft_types', function (Blueprint $table) {
            $table->id();
            $table->string('iata_code')->nullable();        // IATA Code (e.g., 141)
            $table->string('aircraft_name');                // Aircraft Name (e.g., British Aerospace BAe 146-100)
            $table->unsignedBigInteger('plane_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aircraft_types');
    }
};
