<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\AircraftType as MongoAircraftType;
use App\Models\MySql\AircraftType as MySqlAircraftType;

class FetchAircraftTypeJob implements ShouldQueue
{
    use Queueable;

    protected $aircraftTypes;

    /**
     * Create a new job instance.
     *
     * @param array $aircraftTypes
     */
    public function __construct(array $aircraftTypes)
    {
        $this->aircraftTypes = $aircraftTypes;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->aircraftTypes as $aircraftType) {
            MongoAircraftType::updateOrCreate(
                ['plane_type_id' => $aircraftType['plane_type_id']],
                $aircraftType
            );

            MySqlAircraftType::updateOrCreate(
                ['plane_type_id' => $aircraftType['plane_type_id']],
                $aircraftType
            );
        }
    }
}
