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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_id')->unique();       // City ID from the API
            $table->string('iata_code')->nullable();   // IATA Code
            $table->string('country_iso2', 2);         // Country ISO2 code
            $table->string('gmt');                     // GMT Offset
            $table->string('city_name');               // City Name
            $table->string('timezone');                // Timezone
            $table->string('latitude');                // Latitude
            $table->string('longitude');               // Longitude
            $table->unsignedBigInteger('geoname_id')->nullable(); // Geoname ID (nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
