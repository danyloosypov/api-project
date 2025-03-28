<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\Airport as MongoAirport;
use App\Models\MySql\Airport as MySqlAirport;

class FetchAirportsJob implements ShouldQueue
{
    use Queueable;

    protected $airports;

    /**
     * Create a new job instance.
     *
     * @param array $airports
     */
    public function __construct(array $airports)
    {
        $this->airports = $airports;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->airports as $airport) {
            // Update or create the airline in MongoDB
            MongoAirport::updateOrCreate(
                ['airport_id' => $airport['airport_id']],
                $airport
            );

            MySqlAirport::updateOrCreate(
                ['airport_id' => $airport['airport_id']],
                $airport
            );
        }
    }
}
