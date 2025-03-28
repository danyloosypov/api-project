<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\Country as MongoCountry;
use App\Models\MySql\Country as MySqlCountry;

class FetchCountriesJob implements ShouldQueue
{
    use Queueable;

    protected $countries;

    /**
     * Create a new job instance.
     *
     * @param array $countries
     */
    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->countries as $country) {
            MongoCountry::updateOrCreate(
                ['country_id' => $country['country_id']],
                $country
            );

            MySqlCountry::updateOrCreate(
                ['country_id' => $country['country_id']],
                $country
            );
        }
    }
}
