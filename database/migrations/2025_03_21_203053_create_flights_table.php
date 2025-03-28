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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->date('flight_date');
            $table->string('flight_status')->nullable(); // (scheduled, canceled, etc.)

            // Departure information
            $table->string('departure_airport')->nullable();
            $table->string('departure_iata')->nullable();
            $table->string('departure_icao')->nullable();
            $table->string('departure_terminal')->nullable();
            $table->string('departure_gate')->nullable();
            $table->string('departure_delay')->nullable();
            $table->timestamp('departure_scheduled')->nullable();
            $table->timestamp('departure_estimated')->nullable();
            $table->timestamp('departure_actual')->nullable();
            $table->timestamp('departure_estimated_runway')->nullable();
            $table->timestamp('departure_actual_runway')->nullable();

            // Arrival information
            $table->string('arrival_airport')->nullable();
            $table->string('arrival_iata')->nullable();
            $table->string('arrival_icao')->nullable();
            $table->string('arrival_terminal')->nullable();
            $table->string('arrival_gate')->nullable();
            $table->string('arrival_baggage')->nullable();
            $table->string('arrival_delay')->nullable();
            $table->timestamp('arrival_scheduled')->nullable();
            $table->timestamp('arrival_estimated')->nullable();
            $table->timestamp('arrival_actual')->nullable();
            $table->timestamp('arrival_estimated_runway')->nullable();
            $table->timestamp('arrival_actual_runway')->nullable();

            // Airline information
            $table->string('airline_name')->nullable();
            $table->string('airline_iata')->nullable();
            $table->string('airline_icao')->nullable();

            // Flight number and codeshare details
            $table->string('flight_number')->nullable();
            $table->string('flight_iata')->nullable();
            $table->string('flight_icao')->nullable();
            $table->string('flight_codeshared')->nullable(); // Nullable if not available

            // Aircraft and live status information (nullable if not available)
            $table->string('aircraft')->nullable();
            $table->string('live')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
