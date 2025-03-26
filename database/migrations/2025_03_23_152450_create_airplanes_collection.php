<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateAirplanesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('airplanes', function (Blueprint $collection) {
            // Define the fields you might want to index
            $collection->index('guid');
            $collection->index('iata_type');
            $collection->index('airplane_id');
            $collection->index('airline_iata_code');
            $collection->index('iata_code_long');
            $collection->index('iata_code_short');
            $collection->index('airline_icao_code');
            $collection->index('construction_number');
            $collection->index('delivery_date');
            $collection->index('engines_count');
            $collection->index('engines_type');
            $collection->index('first_flight_date');
            $collection->index('icao_code_hex');
            $collection->index('line_number');
            $collection->index('model_code');
            $collection->index('registration_number');
            $collection->index('test_registration_number');
            $collection->index('plane_age');
            $collection->index('plane_class');
            $collection->index('model_name');
            $collection->index('plane_owner');
            $collection->index('plane_series');
            $collection->index('plane_status');
            $collection->index('production_line');
            $collection->index('registration_date');
            $collection->index('rollout_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->table('airplanes', function (Blueprint $collection) {
            // Drop the collection when rolling back
            $collection->drop();
        });
    }
}
