<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

class CreateAircraftTypesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('aircraft_types', function (Blueprint $collection) {
            // Create indexes for fields
            $collection->index('guid');
            $collection->index('iata_code');
            $collection->index('plane_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->table('aircraft_types', function (Blueprint $collection) {
            // Drop the collection or indexes if needed
            $collection->drop();
        });
    }
}
