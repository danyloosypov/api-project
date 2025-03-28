<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Jobs\FetchAirplanesJob;
use App\Models\Mongo\Airplane as MongoAirplane;
use App\Models\MySql\Airplane as MySqlAirplane;

class FetchAirplanes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-airplanes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update airplane data from Aviationstack API.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offset = 0;
        $limit = 100;
        $total = 0;
        $fetchedAirplaneIds = [];

        do {
            // Fetch data from the API
            $data = AviationstackFacade::fetchAirplanesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $airplane) {
                    $fetchedAirplaneIds[] = $airplane['airplane_id'];
                }

                // Dispatch the job to process each chunk of data
                foreach (array_chunk($data['data'], 100) as $chunk) {
                    FetchAirplanesJob::dispatch($chunk);
                }

                // Update the offset for the next API call
                $offset += $limit;
                $total = $data['pagination']['total'];

                // Output progress information
                $this->info("Fetched and dispatched jobs for {$offset} of {$total} records...");
            } else {
                // No more data to fetch
                break;
            }
        } while ($offset < $total);

        // After fetching all data, delete records that are not in the fetched airplane_id list
        if (!empty($fetchedAirplaneIds)) {
            $this->deleteStaleRecords($fetchedAirplaneIds);
        }

        $this->info('Airplane data fetch and dispatch completed successfully.');
    }

    /**
     * Delete records in Mongo and MySQL where airplane_id is not in the fetched data.
     *
     * @param array $fetchedAirplaneIds
     */
    protected function deleteStaleRecords(array $fetchedAirplaneIds)
    {
        // Delete records in MongoDB where airplane_id is not in the fetched data
        $mongoDeleted = MongoAirplane::whereNotIn('airplane_id', $fetchedAirplaneIds)->delete();
        $this->info("Deleted {$mongoDeleted} stale records from MongoDB.");

        // Delete records in MySQL where airplane_id is not in the fetched data
        $mysqlDeleted = MySqlAirplane::whereNotIn('airplane_id', $fetchedAirplaneIds)->delete();
        $this->info("Deleted {$mysqlDeleted} stale records from MySQL.");
    }
}
