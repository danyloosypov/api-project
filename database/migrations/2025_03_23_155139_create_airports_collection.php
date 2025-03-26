<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateAirportsCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('airports', function (Blueprint $collection) {
            // Define indexes for important fields
            $collection->index('guid');
            $collection->index('gmt');
            $collection->index('airport_id');
            $collection->index('iata_code');
            $collection->index('city_iata_code');
            $collection->index('icao_code');
            $collection->index('country_iso2');
            $collection->index('geoname_id');
            $collection->index('latitude');
            $collection->index('longitude');
            $collection->index('airport_name');
            $collection->index('country_name');
            $collection->index('phone_number');
            $collection->index('timezone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->table('airports', function (Blueprint $collection) {
            // Drop the collection when rolling back
            $collection->drop();
        });
    }
}
