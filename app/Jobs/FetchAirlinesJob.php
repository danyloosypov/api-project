<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mongo\Airline as MongoAirline;
use App\Models\MySql\Airline as MySqlAirline;

class FetchAirlinesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $airlines;

    /**
     * Create a new job instance.
     *
     * @param array $airlines
     */
    public function __construct(array $airlines)
    {
        $this->airlines = $airlines;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Process each airline in the chunk
        foreach ($this->airlines as $airline) {
            // Update or create the airline in MongoDB
            MongoAirline::updateOrCreate(
                ['airline_id' => $airline['airline_id']],
                $airline
            );

            // Update or create the airline in MySQL
            MySqlAirline::updateOrCreate(
                ['airline_id' => $airline['airline_id']],
                $airline
            );
        }
    }
}
