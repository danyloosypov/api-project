<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Jobs\FetchAircraftTypeJob;
use App\Models\Mongo\AircraftType as MongoAircraftType;
use App\Models\MySql\AircraftType as MySqlAircraftType;

class FetchAircraftType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-aircraft-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offset = 0;
        $limit = 100;
        $total = 0;
        $requestCount = 0;
        $fetchedPlaneTypeIds = [];

        do {
            // Fetch data from the API
            $data = AviationstackFacade::fetchAircraftTypesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $aircraftType) {
                    $fetchedPlaneTypeIds[] = $aircraftType['plane_type_id'];
                }

                // Process data in chunks of 100 elements
                foreach (array_chunk($data['data'], 100) as $chunk) {
                    // Dispatch the job to process each chunk
                    FetchAircraftTypeJob::dispatch($chunk);
                }

                // Update the offset for the next API call
                $offset += $limit;
                $total = $data['pagination']['total'];

                // Output progress information
                $this->info("Dispatched jobs for {$offset} of {$total} records...");

                $requestCount++;
            } else {
                // No more data to fetch
                break;
            }

            if (app()->environment('local') && $requestCount >= 2) {
                $this->info('Stopping after 2 requests in local environment.');
                break;
            }
        } while ($offset < $total);

        if (!empty($fetchedPlaneTypeIds)) {
            $this->deleteStaleRecords($fetchedPlaneTypeIds);
        }

        $this->info('Aircraft types data fetch and dispatch completed successfully.');
    }

    /**
     * Delete records in Mongo and MySQL where plane_type_id is not in the fetched data.
     *
     * @param array $fetchedPlaneTypeIds
     */
    protected function deleteStaleRecords(array $fetchedPlaneTypeIds)
    {
        // Delete records in MongoDB where plane_type_id is not in the fetched data
        $mongoDeleted = MongoAircraftType::whereNotIn('plane_type_id', $fetchedPlaneTypeIds)->delete();
        $this->info("Deleted {$mongoDeleted} stale records from MongoDB.");

        // Delete records in MySQL where plane_type_id is not in the fetched data
        $mysqlDeleted = MySqlAircraftType::whereNotIn('plane_type_id', $fetchedPlaneTypeIds)->delete();
        $this->info("Deleted {$mysqlDeleted} stale records from MySQL.");
    }
}
