<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\Airline as MongoAirline;
use App\Models\MySql\Airline as MySqlAirline;

class FetchAirline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-airline';

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

        do {
            // Fetch data from the API
            $data = AviationstackFacade::fetchAirlinesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $airline) {
                    MongoAirline::updateOrCreate(
                        ['airline_id' => $airline['airline_id']],
                        $airline
                    );

                    MySqlAirline::updateOrCreate(
                        ['airline_id' => $airline['airline_id']],
                        $airline
                    );
                }

                // Update the offset for the next API call
                $offset += $limit;
                $total = $data['pagination']['total'];

                // Output progress information
                $this->info("Fetched {$offset} of {$total} records...");
            } else {
                // No more data to fetch
                break;
            }
        } while ($offset < $total);

        $this->info('Airline data fetch completed successfully.');
    }
}
