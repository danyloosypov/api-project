<?php

namespace App\Console\Commands\Aviationstack;

use App\Jobs\FetchFlightScheduleJob;
use App\Services\AviationstackService;
use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\FlightSchedule as MongoFlightSchedule;
use App\Models\MySql\FlightSchedule as MySqlFlightSchedule;

class FetchFlightSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flight-schedule';

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
        $iataCode = 'JFK';

        $flightTypes = [
            AviationstackService::TYPE_ARRIVAL,
            AviationstackService::TYPE_DEPARTURE
        ];

        foreach ($flightTypes as $flightType) {
            $this->info("Fetching data for flight type: {$flightType}");

            do {
                // Fetch data from the API
                $data = AviationstackFacade::fetchFlightScheduleData([
                    'offset' => $offset,
                    'limit' => $limit,
                    'iataCode' => $iataCode,
                    'type' => $flightType,
                ]);

                // Check if data is not empty
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $item) {
                        $fetchedIds[] = $item['flight']['number'];
                    }
                    // Process data in chunks of 100 elements
                    foreach (array_chunk($data['data'], 100) as $chunk) {
                        // Dispatch the job to process each chunk
                        FetchFlightScheduleJob::dispatch($chunk);
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

            $offset = 0;
            $total = 0;

            $this->info("Completed fetching data for flight type: {$flightType}");
        }

        $this->info('Flights data fetch completed successfully.');
    }

    /**
     * Delete records in Mongo and MySQL where flight_number is not in the fetched data.
     *
     * @param array $fetchedIds
     */
    protected function deleteStaleRecords(array $fetchedIds)
    {
        // Delete records in MongoDB where airline_id is not in the fetched data
        $mongoDeleted = MongoFlightSchedule::whereNotIn('flight.number', $fetchedIds)->delete();
        $this->info("Deleted {$mongoDeleted} stale records from MongoDB.");

        // Delete records in MySQL where plane_type_id is not in the fetched data
        $mysqlDeleted = MySqlFlightSchedule::whereNotIn('flight_number', $fetchedIds)->delete();
        $this->info("Deleted {$mysqlDeleted} stale records from MySQL.");
    }
}
