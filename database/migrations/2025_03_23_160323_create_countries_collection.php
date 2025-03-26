<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateCountriesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('countries', function (Blueprint $collection) {
            // Define indexes for important fields
            $collection->index('guid');
            $collection->index('country_id');
            $collection->index('country_name');
            $collection->index('capital');
            $collection->index('currency_code');
            $collection->index('currency_name');
            $collection->index('fips_code');
            $collection->index('country_iso2');
            $collection->index('country_iso3');
            $collection->index('country_iso_numeric');
            $collection->index('phone_prefix');
            $collection->index('continent');
            $collection->index('population');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->table('countries', function (Blueprint $collection) {
            // Drop the collection when rolling back
            $collection->drop();
        });
    }
}
