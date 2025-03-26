<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateFlightRoutesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('flight_routes', function (Blueprint $collection) {
            // Indexes for searchable fields
            $collection->index('guid');
            $collection->index('departure.iata');
            $collection->index('arrival.iata');
            $collection->index('airline.iata');
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
        Schema::connection('mongodb')->drop('flight_routes');
    }
}
