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
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->integer('gmt')->nullable();                      // GMT offset (e.g., -10)
            $table->unsignedBigInteger('airport_id');                // Airport ID (e.g., 1)
            $table->string('iata_code', 3)->nullable();              // IATA code (e.g., AAA)
            $table->string('city_iata_code', 3)->nullable();         // City IATA code (e.g., AAA)
            $table->string('icao_code', 4)->nullable();              // ICAO code (e.g., NTGA)
            $table->string('country_iso2', 2)->nullable();           // Country ISO2 (e.g., PF)
            $table->unsignedBigInteger('geoname_id')->nullable();    // GeoName ID (e.g., 6947726)
            $table->decimal('latitude', 10, 8)->nullable();          // Latitude (e.g., -17.05)
            $table->decimal('longitude', 11, 8)->nullable();         // Longitude (e.g., -145.41667)
            $table->string('airport_name')->nullable();              // Airport name (e.g., Anaa)
            $table->string('country_name')->nullable();              // Country name (e.g., French Polynesia)
            $table->string('phone_number')->nullable();              // Phone number (nullable)
            $table->string('timezone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
