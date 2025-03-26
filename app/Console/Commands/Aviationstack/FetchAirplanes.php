<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
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
            $data = AviationstackFacade::fetchAirplanesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $airplane) {
                    MongoAirplane::updateOrCreate(
                        ['airplane_id' => $airplane['airplane_id']],
                        $airplane
                    );

                    MySqlAirplane::updateOrCreate(
                        ['airplane_id' => $airplane['airplane_id']],
                        $airplane
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

        $this->info('Airplane data fetch completed successfully.');
    }
}
