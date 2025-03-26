<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateCitiesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('cities', function (Blueprint $collection) {
            // Define indexes for important fields
            $collection->index('guid');
            $collection->index('city_id');
            $collection->index('iata_code');
            $collection->index('country_iso2');
            $collection->index('gmt');
            $collection->index('city_name');
            $collection->index('timezone');
            $collection->index('latitude');
            $collection->index('longitude');
            $collection->index('geoname_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->table('cities', function (Blueprint $collection) {
            // Drop the collection when rolling back
            $collection->drop();
        });
    }
}
