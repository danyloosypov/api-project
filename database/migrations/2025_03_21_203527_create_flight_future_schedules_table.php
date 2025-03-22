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
        Schema::create('flight_future_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('weekday'); // 1 = Sunday, 2 = Monday, etc.

            // Flight information
            $table->string('flight_number')->nullable();
            $table->string('flight_iata_number')->nullable();
            $table->string('flight_icao_number')->nullable();

            // Airline information
            $table->string('airline_name')->nullable();
            $table->string('airline_iata_code', 3)->nullable();
            $table->string('airline_icao_code', 3)->nullable();

            // Aircraft information
            $table->string('aircraft_model_code')->nullable();
            $table->string('aircraft_model_text')->nullable();

            // Departure information
            $table->string('departure_iata_code', 3)->nullable();
            $table->string('departure_icao_code', 4)->nullable();
            $table->string('departure_terminal')->nullable();
            $table->string('departure_gate')->nullable();
            $table->time('departure_scheduled_time')->nullable();

            // Arrival information
            $table->string('arrival_iata_code', 3)->nullable();
            $table->string('arrival_icao_code', 4)->nullable();
            $table->string('arrival_terminal')->nullable();
            $table->string('arrival_gate')->nullable();
            $table->time('arrival_scheduled_time')->nullable();

            // Codeshare flight information
            $table->string('codeshare_airline_name')->nullable();
            $table->string('codeshare_airline_iata_code', 3)->nullable();
            $table->string('codeshare_airline_icao_code', 3)->nullable();
            $table->string('codeshare_flight_number')->nullable();
            $table->string('codeshare_flight_iata_number')->nullable();
            $table->string('codeshare_flight_icao_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_future_schedules');
    }
};
