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
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->decimal('fleet_average_age', 4, 1)->nullable();   // Fleet average age (e.g., 10.9)
            $table->unsignedBigInteger('airline_id');                // Airline ID (e.g., 1)
            $table->string('callsign')->nullable();                  // Callsign (e.g., AMERICAN)
            $table->string('hub_code')->nullable();                  // Hub code (e.g., DFW)
            $table->string('iata_code', 3)->nullable();              // IATA code (e.g., AA)
            $table->string('icao_code', 3)->nullable();              // ICAO code (e.g., AAL)
            $table->string('country_iso2', 2)->nullable();           // Country ISO2 (e.g., US)
            $table->year('date_founded')->nullable();                // Date founded (e.g., 1934)
            $table->string('iata_prefix_accounting')->nullable();    // IATA prefix accounting (e.g., 1)
            $table->string('airline_name');                          // Airline name (e.g., American Airlines)
            $table->string('country_name')->nullable();              // Country name (e.g., United States)
            $table->unsignedInteger('fleet_size')->nullable();       // Fleet size (e.g., 963)
            $table->string('status')->nullable();                    // Status (e.g., active)
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airlines');
    }
};
