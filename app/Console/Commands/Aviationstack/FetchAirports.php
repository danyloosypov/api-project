<?php

namespace App\Console\Commands\Aviationstack;

use App\Jobs\FetchAirportsJob;
use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\Airport as MongoAirport;
use App\Models\MySql\Airport as MySqlAirport;

class FetchAirports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-airports';

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
        $requestCount = 0; // Track the number of requests made
        $fetchedIds = [];

        do {
            // Fetch data from the API
            $data = AviationstackFacade::fetchAirportsData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $item) {
                    $fetchedIds[] = $item['airport_id'];
                }
                // Process data in chunks of 100 elements
                foreach (array_chunk($data['data'], 100) as $chunk) {
                    // Dispatch the job to process each chunk
                    FetchAirportsJob::dispatch($chunk);
                }

                // Update the offset for the next API call
                $offset += $limit;
                $total = $data['pagination']['total'];

                $this->info("Dispatched jobs for {$offset} of {$total} records...");

                // Increase the request count
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

        if (!empty($fetchedIds)) {
            $this->deleteStaleRecords($fetchedIds);
        }

        $this->info('Airports data fetch and dispatch completed successfully.');
    }

    /**
     * Delete records in Mongo and MySQL where airport_id is not in the fetched data.
     *
     * @param array $fetchedIds
     */
    protected function deleteStaleRecords(array $fetchedIds)
    {
        // Delete records in MongoDB where airline_id is not in the fetched data
        $mongoDeleted = MongoAirport::whereNotIn('airport_id', $fetchedIds)->delete();
        $this->info("Deleted {$mongoDeleted} stale records from MongoDB.");

        // Delete records in MySQL where plane_type_id is not in the fetched data
        $mysqlDeleted = MySqlAirport::whereNotIn('airport_id', $fetchedIds)->delete();
        $this->info("Deleted {$mysqlDeleted} stale records from MySQL.");
    }
}
