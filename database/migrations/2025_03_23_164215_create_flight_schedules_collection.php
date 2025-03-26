<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateFlightSchedulesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('flight_schedules', function (Blueprint $collection) {
            // Indexes for frequently queried fields
            $collection->index('guid');
            $collection->index('airline.iataCode');
            $collection->index('departure.iataCode');
            $collection->index('arrival.iataCode');
            $collection->index('flight.iataNumber');
            $collection->index('status');
            $collection->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->drop('flight_schedules');
    }
}
