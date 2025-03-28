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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('capital')->nullable();                  // Capital city (e.g., Andorra la Vella)
            $table->string('currency_code', 3)->nullable();         // Currency code (e.g., EUR)
            $table->string('fips_code', 2)->nullable();             // FIPS code (e.g., AN)
            $table->string('country_iso2', 2)->nullable();          // ISO 2 country code (e.g., AD)
            $table->string('country_iso3', 3)->nullable();          // ISO 3 country code (e.g., AND)
            $table->string('continent', 2)->nullable();             // Continent code (e.g., EU)
            $table->unsignedBigInteger('country_id');               // Country ID (e.g., 1)
            $table->string('country_name')->nullable();              // Country name (e.g., Andorra)
            $table->string('currency_name')->nullable();             // Currency name (e.g., Euro)
            $table->unsignedBigInteger('country_iso_numeric');      // ISO Numeric (e.g., 20)
            $table->string('phone_prefix')->nullable();             // Phone prefix (e.g., 376)
            $table->unsignedBigInteger('population')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
