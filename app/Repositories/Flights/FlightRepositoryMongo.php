<?php

namespace App\Repositories\Flights;

use App\Models\Mongo\AircraftType;
use App\Models\Mongo\Airline;
use App\Models\Mongo\Airplane;
use App\Models\Mongo\Airport;
use App\Models\Mongo\City;
use App\Models\Mongo\Country;
use App\Models\Mongo\Flight;
use App\Models\Mongo\FlightFutureSchedule;
use App\Models\Mongo\FlightRoute;
use App\Models\Mongo\FlightSchedule;
use Illuminate\Http\Request;

class FlightRepositoryMongo implements FlightMongoRepositoryInterface
{

    public function getFlights(Request $request)
    {
        $query = Flight::query();

        $flatFilters = [
            'flight_status',
            'flight_date',
        ];

        foreach ($flatFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        $nestedFilters = [
            'dep_iata'      => 'departure.iata',
            'arr_iata'      => 'arrival.iata',
            'dep_icao'      => 'departure.icao',
            'arr_icao'      => 'arrival.icao',
            'airline_name'  => 'airline.name',
            'airline_iata'  => 'airline.iata',
            'airline_icao'  => 'airline.icao',
            'flight_number' => 'flight.number',
            'flight_iata'   => 'flight.iata',
            'flight_icao'   => 'flight.icao',
        ];

        foreach ($nestedFilters as $inputKey => $fieldPath) {
            if ($request->filled($inputKey)) {
                $query->where($fieldPath, $request->input($inputKey));
            }
        }

        if ($request->filled('min_delay_dep')) {
            $query->where('departure.delay', '>=', (int) $request->input('min_delay_dep'));
        }
        if ($request->filled('max_delay_dep')) {
            $query->where('departure.delay', '<=', (int) $request->input('max_delay_dep'));
        }
        if ($request->filled('min_delay_arr')) {
            $query->where('arrival.delay', '>=', (int) $request->input('min_delay_arr'));
        }
        if ($request->filled('max_delay_arr')) {
            $query->where('arrival.delay', '<=', (int) $request->input('max_delay_arr'));
        }

        // Sorting
        $sortFieldMap = [
            'flight_date'     => 'flight_date',
            'flight_status'   => 'flight_status',
            'departure_iata'  => 'departure.iata',
            'arrival_iata'    => 'arrival.iata',
            'airline_name'    => 'airline.name',
        ];

        $sortField = $request->input('sort', 'flight_date');
        $sortDirection = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $sortField = $sortFieldMap[$sortField] ?? 'flight_date';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate(50);
    }

    public function getFlightRoutes(Request $request)
    {
        $query = FlightRoute::query();

        // Flat filter (nested as subdocument)
        if ($request->filled('flight_number')) {
            $query->where('flight.number', $request->input('flight_number'));
        }

        // Departure filters
        if ($request->filled('dep_iata')) {
            $query->where('departure.iata', $request->input('dep_iata'));
        }
        if ($request->filled('dep_icao')) {
            $query->where('departure.icao', $request->input('dep_icao'));
        }

        // Arrival filters
        if ($request->filled('arr_iata')) {
            $query->where('arrival.iata', $request->input('arr_iata'));
        }
        if ($request->filled('arr_icao')) {
            $query->where('arrival.icao', $request->input('arr_icao'));
        }

        // Airline filters
        if ($request->filled('airline_iata')) {
            $query->where('airline.iata', $request->input('airline_iata'));
        }
        if ($request->filled('airline_icao')) {
            $query->where('airline.icao', $request->input('airline_icao'));
        }

        // Pagination
        $flightRoutes = $query->paginate(50);

        return $flightRoutes;
    }

    public function getAirports(Request $request)
    {
        $query = Airport::query();

        if ($request->has('search')) {
            $query->where('airport_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('iata_code', 'like', '%' . $request->input('search') . '%')
                ->orWhere('icao_code', 'like', '%' . $request->input('search') . '%');
        }

        // Return paginated results
        $airports = $query->paginate(50);

        return $airports;
    }

    public function getAirlines(Request $request)
    {
        $query = Airline::query();

        // Apply search filter if the 'search' parameter is present
        if ($request->has('search')) {
            $query->where('airline_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('iata_code', 'like', '%' . $request->input('search') . '%')
                ->orWhere('icao_code', 'like', '%' . $request->input('search') . '%');
        }

        $airlines = $query->paginate(50);

        return $airlines;
    }

    public function getAirplanes(Request $request)
    {
        $query = Airplane::query();

        // Apply search filter if 'search' parameter is present
        if ($request->has('search')) {
            $query->where('model_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('registration_number', 'like', '%' . $request->input('search') . '%')
                ->orWhere('iata_code_long', 'like', '%' . $request->input('search') . '%')
                ->orWhere('iata_code_short', 'like', '%' . $request->input('search') . '%')
                ->orWhere('icao_code_hex', 'like', '%' . $request->input('search') . '%');
        }

        $airplanes = $query->paginate(50);

        return $airplanes;
    }

    public function getAircraftTypes(Request $request)
    {
        $query = AircraftType::query();

        // Apply search filter if 'search' parameter is present
        if ($request->has('search')) {
            $query->where('aircraft_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('iata_code', 'like', '%' . $request->input('search') . '%')
                ->orWhere('plane_type_id', 'like', '%' . $request->input('search') . '%');
        }

        $airplanes = $query->paginate(50);

        return $airplanes;
    }

    public function getCities(Request $request)
    {
        $query = City::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('city_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('iata_code', 'LIKE', "%{$searchTerm}%");
        }

        return $query->paginate(50);
    }

    public function getCountries(Request $request)
    {
        $query = Country::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('country_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('country_iso2', 'LIKE', "%{$searchTerm}%")
                ->orWhere('country_iso3', 'LIKE', "%{$searchTerm}%");
        }

        return $query->paginate(50);
    }

    public function getFlightSchedules(Request $request)
    {
        $query = FlightSchedule::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Mandatory filters
        if ($request->has('iataCode')) {
            $query->where(function ($q) use ($request) {
                $q->where('departure.iataCode', $request->iataCode)
                    ->orWhere('arrival.iataCode', $request->iataCode);
            });
        }

        if ($request->has('dep_terminal')) {
            $query->where('departure.terminal', $request->dep_terminal);
        }

        if ($request->has('dep_schTime')) {
            $query->where('departure.scheduledTime', $request->dep_schTime);
        }

        if ($request->has('dep_estTime')) {
            $query->where('departure.estimatedTime', $request->dep_estTime);
        }

        if ($request->has('dep_actTime')) {
            $query->where('departure.actualTime', $request->dep_actTime);
        }

        if ($request->has('arr_terminal')) {
            $query->where('arrival.terminal', $request->arr_terminal);
        }

        if ($request->has('arr_schTime')) {
            $query->where('arrival.scheduledTime', $request->arr_schTime);
        }

        if ($request->has('arr_estTime')) {
            $query->where('arrival.estimatedTime', $request->arr_estTime);
        }

        if ($request->has('arr_actTime')) {
            $query->where('arrival.actualTime', $request->arr_actTime);
        }

        if ($request->has('airline_name')) {
            $query->where('airline.name', 'LIKE', "%{$request->airline_name}%");
        }

        if ($request->has('airline_iata')) {
            $query->where('airline.iataCode', $request->airline_iata);
        }

        if ($request->has('airline_icao')) {
            $query->where('airline.icaoCode', $request->airline_icao);
        }

        if ($request->has('flight_num')) {
            $query->where('flight.number', $request->flight_num);
        }

        if ($request->has('flight_iata')) {
            $query->where('flight.iataNumber', $request->flight_iata);
        }

        if ($request->has('flight_icao')) {
            $query->where('flight.icaoNumber', $request->flight_icao);
        }

        // Pagination
        return $query->paginate(20);
    }

    public function getFlightFutureSchedules(Request $request)
    {
        $query = FlightFutureSchedule::query();

        // Required filters
        if ($request->has('iataCode')) {
            $query->where(function ($q) use ($request) {
                $q->where('departure.iataCode', $request->iataCode)
                    ->orWhere('arrival.iataCode', $request->iataCode);
            });
        }

        // Optional filters
        if ($request->has('airline_iata')) {
            $query->where('airline.iataCode', $request->airline_iata);
        }

        if ($request->has('airline_icao')) {
            $query->where('airline.icaoCode', $request->airline_icao);
        }

        if ($request->has('flight_number')) {
            $query->where('flight.number', $request->flight_number);
        }

        // Pagination
        return $query->paginate(20);
    }
}
