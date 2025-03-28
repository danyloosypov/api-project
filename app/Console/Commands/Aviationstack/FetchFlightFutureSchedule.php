<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\FlightFutureSchedule as MongoFlightFutureSchedule;
use App\Models\MySql\FlightFutureSchedule as MySqlFlightFutureSchedule;
use App\Jobs\FetchFlightFutureScheduleJob;
use Illuminate\Support\Facades\Log;
use App\Services\AviationstackService;

class FetchFlightFutureSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flight-future-schedule';

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
        $iataCode = 'AHZ';
        $date = '2025-04-04';

        // Define the flight types (arrival and departure)
        $flightTypes = [
            AviationstackService::TYPE_ARRIVAL,
            AviationstackService::TYPE_DEPARTURE
        ];

        foreach ($flightTypes as $flightType) {
            $this->info("Fetching data for flight type: {$flightType}");

            do {
                // Fetch data from the API
                $data = AviationstackFacade::fetchFlightFutureSchedulesData([
                    'offset' => $offset,
                    'limit' => $limit,
                    'iataCode' => $iataCode,
                    'type' => $flightType,
                    'date' => $date,
                ]);

                // Check if data is not empty
                if (!empty($data['data'])) {
                    $this->deleteExistingRecords($flightType); // Pass the type for more granularity if needed

                    foreach (array_chunk($data['data'], 100) as $chunk) {
                        // Dispatch the job to process each chunk
                        FetchFlightFutureScheduleJob::dispatch($chunk);
                    }

                    // Update the offset for the next API call
                    $offset += $limit;
                    $total = $data['pagination']['total'];

                    $this->info("Dispatched jobs for {$offset} of {$total} records for {$flightType}...");

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

            // Reset offset and total for the next flight type
            $offset = 0;
            $total = 0;

            $this->info("Completed fetching data for flight type: {$flightType}");
        }

        $this->info('Flights data fetch completed successfully for all types.');
    }

    protected function deleteExistingRecords(): void
    {
        MongoFlightFutureSchedule::truncate();
        MySqlFlightFutureSchedule::truncate();
        $this->info('Deleted all existing records from MongoDB and MySQL.');
    }
}
