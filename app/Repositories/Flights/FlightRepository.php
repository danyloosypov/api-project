<?php

namespace App\Repositories\Flights;

use App\Models\MySql\AircraftType;
use App\Models\MySql\Airline;
use App\Models\MySql\Airplane;
use App\Models\MySql\Airport;
use App\Models\MySql\City;
use App\Models\MySql\Country;
use App\Models\MySql\Flight;
use App\Models\MySql\FlightFutureSchedule;
use App\Models\MySql\FlightRoute;
use App\Models\MySql\FlightSchedule;
use Illuminate\Http\Request;

class FlightRepository implements FlightRepositoryInterface
{

    public function getFlights(Request $request)
    {
        $query = Flight::query();

        // Apply filters
        $filters = [
            'flight_status', 'flight_date', 'dep_iata', 'arr_iata', 'dep_icao', 'arr_icao',
            'airline_name', 'airline_iata', 'airline_icao', 'flight_number', 'flight_iata', 'flight_icao'
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        // Apply delay filters
        if ($request->filled('min_delay_dep')) {
            $query->where('departure_delay', '>=', $request->input('min_delay_dep'));
        }
        if ($request->filled('max_delay_dep')) {
            $query->where('departure_delay', '<=', $request->input('max_delay_dep'));
        }
        if ($request->filled('min_delay_arr')) {
            $query->where('arrival_delay', '>=', $request->input('min_delay_arr'));
        }
        if ($request->filled('max_delay_arr')) {
            $query->where('arrival_delay', '<=', $request->input('max_delay_arr'));
        }

        // Sorting
        $sortField = $request->input('sort', 'flight_date'); // Default sort field
        $sortDirection = $request->input('direction', 'asc'); // Default sort order

        if (!in_array($sortField, ['flight_date', 'flight_status', 'departure_iata', 'arrival_iata', 'airline_name'])) {
            $sortField = 'flight_date';
        }
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = 50;
        return $query->paginate($perPage);
    }

    public function getFlightRoutes(Request $request)
    {
        $query = FlightRoute::query();

        // Apply filters based on request parameters
        if ($request->has('flight_number')) {
            $query->where('flight_number', $request->input('flight_number'));
        }

        if ($request->has('dep_iata')) {
            $query->where('departure_iata', $request->input('dep_iata'));
        }

        if ($request->has('arr_iata')) {
            $query->where('arrival_iata', $request->input('arr_iata'));
        }

        if ($request->has('dep_icao')) {
            $query->where('departure_icao', $request->input('dep_icao'));
        }

        if ($request->has('arr_icao')) {
            $query->where('arrival_icao', $request->input('arr_icao'));
        }

        if ($request->has('airline_iata')) {
            $query->where('airline_iata', $request->input('airline_iata'));
        }

        if ($request->has('airline_icao')) {
            $query->where('airline_icao', $request->input('airline_icao'));
        }

        // Pagination
        $flightRoutes = $query->paginate(50); // Adjust the number (50) to your pagination needs

        // Return the response with the flight routes data
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

        // Mandatory filters
        if ($request->has('iataCode')) {
            $query->where(function ($q) use ($request) {
                $q->where('departure_iata', $request->iataCode)
                    ->orWhere('arrival_iata', $request->iataCode);
            });
        }

        if ($request->has('type')) {
            if ($request->type === 'departure') {
                $query->whereNotNull('departure_iata');
            } elseif ($request->type === 'arrival') {
                $query->whereNotNull('arrival_iata');
            }
        }

        // Optional filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('dep_terminal')) {
            $query->where('departure_terminal', $request->dep_terminal);
        }

        if ($request->has('dep_delay')) {
            $query->where('departure_delay', '>=', $request->dep_delay);
        }

        if ($request->has('dep_schTime')) {
            $query->where('departure_scheduled', $request->dep_schTime);
        }

        if ($request->has('dep_estTime')) {
            $query->where('departure_estimated', $request->dep_estTime);
        }

        if ($request->has('dep_actTime')) {
            $query->where('departure_actual', $request->dep_actTime);
        }

        if ($request->has('arr_terminal')) {
            $query->where('arrival_terminal', $request->arr_terminal);
        }

        if ($request->has('arr_delay')) {
            $query->where('arrival_delay', '>=', $request->arr_delay);
        }

        if ($request->has('arr_schTime')) {
            $query->where('arrival_scheduled', $request->arr_schTime);
        }

        if ($request->has('arr_estTime')) {
            $query->where('arrival_estimated', $request->arr_estTime);
        }

        if ($request->has('arr_actTime')) {
            $query->where('arrival_actual', $request->arr_actTime);
        }

        if ($request->has('airline_name')) {
            $query->where('airline_name', 'LIKE', "%{$request->airline_name}%");
        }

        if ($request->has('airline_iata')) {
            $query->where('airline_iata', $request->airline_iata);
        }

        if ($request->has('airline_icao')) {
            $query->where('airline_icao', $request->airline_icao);
        }

        if ($request->has('flight_num')) {
            $query->where('flight_number', $request->flight_num);
        }

        if ($request->has('flight_iata')) {
            $query->where('flight_iata', $request->flight_iata);
        }

        if ($request->has('flight_icao')) {
            $query->where('flight_icao', $request->flight_icao);
        }

        if ($request->has('lang')) {
            $query->where('language', $request->lang);
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
                $q->where('departure_iata_code', $request->iataCode)
                    ->orWhere('arrival_iata_code', $request->iataCode);
            });
        }

        if ($request->has('type')) {
            if ($request->type === 'departure') {
                $query->whereNotNull('departure_iata_code');
            } elseif ($request->type === 'arrival') {
                $query->whereNotNull('arrival_iata_code');
            }
        }

        if ($request->has('date')) {
            $query->whereDate('departure_scheduled_time', $request->date);
        }

        // Optional filters
        if ($request->has('airline_iata')) {
            $query->where('airline_iata_code', $request->airline_iata);
        }

        if ($request->has('airline_icao')) {
            $query->where('airline_icao_code', $request->airline_icao);
        }

        if ($request->has('flight_number')) {
            $query->where('flight_number', $request->flight_number);
        }

        // Pagination
        return $query->paginate(20);
    }
}
