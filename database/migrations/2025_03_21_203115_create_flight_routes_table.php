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
        Schema::create('flight_routes', function (Blueprint $table) {
            $table->id();
            // Departure information
            $table->string('departure_airport')->nullable();       // Departure airport name (e.g., Viru Viru International)
            $table->string('departure_iata', 3)->nullable();       // Departure IATA code (e.g., VVI)
            $table->string('departure_icao', 4)->nullable();       // Departure ICAO code (e.g., SLVR)
            $table->string('departure_timezone')->nullable();     // Departure timezone (e.g., America/La_Paz)
            $table->time('departure_time')->nullable();           // Departure time (e.g., 09:45:00)
            $table->string('departure_terminal')->nullable();     // Departure terminal (nullable)

            // Arrival information
            $table->string('arrival_airport')->nullable();        // Arrival airport name (e.g., J Wilsterman)
            $table->string('arrival_iata', 3)->nullable();        // Arrival IATA code (e.g., CBB)
            $table->string('arrival_icao', 4)->nullable();        // Arrival ICAO code (e.g., SLCB)
            $table->string('arrival_timezone')->nullable();      // Arrival timezone (e.g., America/La_Paz)
            $table->time('arrival_time')->nullable();            // Arrival time (e.g., 10:30:00)
            $table->string('arrival_terminal')->nullable();      // Arrival terminal (nullable)

            // Airline information
            $table->string('airline_name')->nullable();          // Airline name (e.g., Amaszonas S.A)
            $table->string('airline_callsign')->nullable();      // Airline callsign (e.g., AMAZONAS)
            $table->string('airline_iata', 3)->nullable();       // Airline IATA code (e.g., Z8)
            $table->string('airline_icao', 4)->nullable();       // Airline ICAO code (e.g., AZN)

            // Flight number
            $table->string('flight_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_routes');
    }
};
