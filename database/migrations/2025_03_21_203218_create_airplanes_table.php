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
        Schema::create('airplanes', function (Blueprint $table) {
            $table->id();
            $table->string('iata_type')->nullable();                // IATA type (e.g., B737-300)
            $table->unsignedBigInteger('airplane_id');              // Airplane ID (e.g., 1)
            $table->string('airline_iata_code')->nullable();        // Airline IATA code (e.g., 0B)
            $table->string('iata_code_long')->nullable();           // Long IATA code (e.g., B733)
            $table->string('iata_code_short')->nullable();          // Short IATA code (e.g., 733)
            $table->string('airline_icao_code')->nullable();        // Airline ICAO code (nullable)
            $table->string('construction_number')->nullable();      // Construction number (e.g., 23653)
            $table->date('delivery_date')->nullable();              // Delivery date
            $table->integer('engines_count')->nullable();           // Number of engines (e.g., 2)
            $table->string('engines_type')->nullable();             // Engine type (e.g., JET)
            $table->date('first_flight_date')->nullable();          // First flight date
            $table->string('icao_code_hex')->nullable();            // ICAO code hex (e.g., 4A0823)
            $table->string('line_number')->nullable();              // Line number (e.g., 1260)
            $table->string('model_code')->nullable();               // Model code (e.g., B737-377)
            $table->string('registration_number')->nullable();      // Registration number (e.g., YR-BAC)
            $table->string('test_registration_number')->nullable(); // Test registration number
            $table->integer('plane_age')->nullable();               // Age of the plane (e.g., 31)
            $table->string('plane_class')->nullable();              // Plane class (nullable)
            $table->string('model_name')->nullable();               // Model name (e.g., 737)
            $table->string('plane_owner')->nullable();              // Owner of the plane (e.g., Airwork Flight Operations Ltd)
            $table->string('plane_series')->nullable();             // Plane series (e.g., 377)
            $table->string('plane_status')->nullable();             // Status of the plane (e.g., active)
            $table->string('production_line')->nullable();          // Production line (e.g., Boeing 737 Classic)
            $table->date('registration_date')->nullable();          // Registration date
            $table->date('rollout_date')->nullable();               // Rollout date (nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airplanes');
    }
};
