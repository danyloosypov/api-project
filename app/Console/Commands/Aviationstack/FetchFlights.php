<?php

namespace App\Console\Commands\Aviationstack;

use App\Jobs\FetchFlightsJob;
use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\Flight as MongoFlight;
use App\Models\MySql\Flight as MySqlFlight;

class FetchFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch flights data from Aviationstack API and store or update in MongoDB and MySQL';

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
            $data = AviationstackFacade::fetchFlightsData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $item) {
                    $fetchedIds[] = array($item['flight']['iata'], $item['flight_date']);
                }
                // Process data in chunks of 100 elements
                foreach (array_chunk($data['data'], 100) as $chunk) {
                    // Dispatch the job to process each chunk
                    FetchFlightsJob::dispatch($chunk);
                }

                // Update the offset for the next API call
                $offset += $limit;
                $total = $data['pagination']['total'];

                // Output progress information
                $this->info("Fetched {$offset} of {$total} records...");

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

        $this->info('Flights data fetch completed successfully.');
    }

    /**
     * Delete records in MongoDB and MySQL where flight_iata and flight_date are not in the fetched data.
     *
     * @param array $fetchedIds
     */
    protected function deleteStaleRecords(array $fetchedIds)
    {
        $flightsToKeep = collect($fetchedIds)->map(function ($item) {
            return ['flight_iata' => $item[0], 'flight_date' => $item[1]];
        });

        // MongoDB: Delete records where flight_iata and flight_date are NOT in the fetched results
        $mongoDeleted = MongoFlight::whereNotIn('flight.flight_iata', $flightsToKeep->pluck('flight_iata'))
            ->orWhereNotIn('flight_date', $flightsToKeep->pluck('flight_date'))
            ->delete();
        $this->info("Deleted {$mongoDeleted} stale records from MongoDB.");

        // MySQL: Delete records where flight_iata and flight_date are NOT in the fetched results
        $mysqlDeleted = MySqlFlight::whereNotIn('flight_iata', $flightsToKeep->pluck('flight_iata'))
            ->orWhereNotIn('flight_date', $flightsToKeep->pluck('flight_date'))
            ->delete();
        $this->info("Deleted {$mysqlDeleted} stale records from MySQL.");
    }

}
