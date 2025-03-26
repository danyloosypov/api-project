<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
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

        do {
            // Fetch data from the API
            $data = AviationstackFacade::fetchAircraftTypesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $aircraftType) {
                    MongoAircraftType::updateOrCreate(
                        ['plane_type_id' => $aircraftType['plane_type_id']],
                        $aircraftType
                    );

                    MySqlAircraftType::updateOrCreate(
                        ['plane_type_id' => $aircraftType['plane_type_id']],
                        $aircraftType
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

        $this->info('Aircraft types data fetch completed successfully.');
    }
}
