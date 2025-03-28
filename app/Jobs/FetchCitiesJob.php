<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\City as MongoCity;
use App\Models\MySql\City as MySqlCity;

class FetchCitiesJob implements ShouldQueue
{
    use Queueable;

    protected $cities;

    /**
     * Create a new job instance.
     *
     * @param array $cities
     */
    public function __construct(array $cities)
    {
        $this->cities = $cities;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->cities as $city) {
            MongoCity::updateOrCreate(
                ['city_id' => $city['city_id']],
                $city
            );

            MySqlCity::updateOrCreate(
                ['city_id' => $city['city_id']],
                $city
            );
        }
    }
}
