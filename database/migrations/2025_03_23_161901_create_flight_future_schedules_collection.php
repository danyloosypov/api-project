<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateFlightFutureSchedulesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('flight_future_schedules', function (Blueprint $collection) {
            // Indexes for searchable fields
            $collection->index('guid');
            $collection->index('weekday');
            $collection->index('departure.iataCode');
            $collection->index('arrival.iataCode');
            $collection->index('airline.iataCode');
            $collection->index('flight.number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->drop('flight_future_schedules');
    }
}
