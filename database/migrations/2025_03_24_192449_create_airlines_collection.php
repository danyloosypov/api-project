<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('airlines', function (Blueprint $collection) {
            $collection->string('fleet_average_age')->nullable();
            $collection->string('airline_id')->unique();
            $collection->string('callsign')->nullable();
            $collection->string('hub_code')->nullable();
            $collection->string('iata_code')->nullable();
            $collection->string('icao_code')->nullable();
            $collection->string('country_iso2')->nullable();
            $collection->string('date_founded')->nullable();
            $collection->string('iata_prefix_accounting')->nullable();
            $collection->string('airline_name');
            $collection->string('country_name')->nullable();
            $collection->integer('fleet_size')->nullable();
            $collection->string('status')->nullable();
            $collection->string('type')->nullable();

            // Add timestamps if needed
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->drop('airlines');
    }
};
