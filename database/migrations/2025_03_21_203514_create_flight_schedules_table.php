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
        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->id();
            // Airline information
            $table->string('airline_name')->nullable();
            $table->string('airline_iata_code')->nullable();
            $table->string('airline_icao_code')->nullable();

            // Flight information
            $table->string('flight_number')->nullable();
            $table->string('flight_iata_number')->nullable();
            $table->string('flight_icao_number')->nullable();

            // Departure information
            $table->string('departure_iata_code')->nullable();
            $table->string('departure_icao_code')->nullable();
            $table->timestamp('departure_scheduled_time')->nullable();
            $table->timestamp('departure_estimated_time')->nullable();
            $table->timestamp('departure_actual_time')->nullable();
            $table->string('departure_gate')->nullable();
            $table->integer('departure_delay')->nullable(); // in minutes
            $table->string('departure_baggage')->nullable();
            $table->string('departure_actual_runway')->nullable();
            $table->string('departure_estimated_runway')->nullable();

            // Arrival information
            $table->string('arrival_iata_code')->nullable();
            $table->string('arrival_icao_code')->nullable();
            $table->timestamp('arrival_scheduled_time')->nullable();
            $table->timestamp('arrival_estimated_time')->nullable();
            $table->timestamp('arrival_actual_time')->nullable();
            $table->string('arrival_gate')->nullable();
            $table->string('arrival_baggage')->nullable();
            $table->string('arrival_actual_runway')->nullable();
            $table->string('arrival_estimated_runway')->nullable();

            // Additional fields
            $table->string('codeshared')->nullable();
            $table->string('status')->nullable(); // (active, canceled, etc.)
            $table->string('type')->nullable(); // (departure, arrival, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_schedules');
    }
};
