<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mongo\Airplane as MongoAirplane;
use App\Models\MySql\Airplane as MySqlAirplane;

class FetchAirplanesJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $airplanes;

    /**
     * Create a new job instance.
     *
     * @param array $airplanes
     */
    public function __construct(array $airplanes)
    {
        $this->airplanes = $airplanes;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Process each airplane in the chunk
        foreach ($this->airplanes as $airplane) {
            // Update or create in MongoDB
            MongoAirplane::updateOrCreate(
                ['airplane_id' => $airplane['airplane_id']],
                $airplane
            );

            // Update or create in MySQL
            MySqlAirplane::updateOrCreate(
                ['airplane_id' => $airplane['airplane_id']],
                $airplane
            );
        }
    }
}
