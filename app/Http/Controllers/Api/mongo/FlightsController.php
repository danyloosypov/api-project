<?php

namespace App\Http\Controllers\Api\mongo;

use App\Http\Controllers\Controller;
use App\Services\FlightServiceMongo;
use Illuminate\Http\Request;

class FlightsController extends Controller
{
    protected $flightService;

    public function __construct(FlightServiceMongo $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/flights",
     *     summary="Get a list of flights",
     *     description="Retrieve flights with optional filters, sorting, and pagination.",
     *     tags={"Flights"},
     *     @OA\Parameter(name="flight_status", in="query", description="Filter by flight status (scheduled, active, landed, cancelled, incident, diverted)", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="flight_date", in="query", description="Filter by flight date (format: YYYY-MM-DD)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="dep_iata", in="query", description="Filter by departure airport IATA code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="arr_iata", in="query", description="Filter by arrival airport IATA code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="dep_icao", in="query", description="Filter by departure airport ICAO code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="arr_icao", in="query", description="Filter by arrival airport ICAO code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="airline_name", in="query", description="Filter by airline name", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="airline_iata", in="query", description="Filter by airline IATA code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="airline_icao", in="query", description="Filter by airline ICAO code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="flight_number", in="query", description="Filter by flight number", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="flight_iata", in="query", description="Filter by flight IATA code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="flight_icao", in="query", description="Filter by flight ICAO code", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="min_delay_dep", in="query", description="Filter by minimum departure delay (minutes)", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="min_delay_arr", in="query", description="Filter by minimum arrival delay (minutes)", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="max_delay_dep", in="query", description="Filter by maximum departure delay (minutes)", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="max_delay_arr", in="query", description="Filter by maximum arrival delay (minutes)", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="arr_scheduled_time_arr", in="query", description="Filter by scheduled arrival date (YYYY-MM-DD)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="arr_scheduled_time_dep", in="query", description="Filter by scheduled departure date (YYYY-MM-DD)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="sort", in="query", description="Field to sort the results by", required=false, @OA\Schema(type="string", default="flight_date")),
     *     @OA\Parameter(name="direction", in="query", description="Sort direction (asc or desc)", required=false, @OA\Schema(type="string", default="asc")),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="flight_date", type="string", format="date"),
     *                 @OA\Property(property="flight_status", type="string"),
     *                 @OA\Property(property="departure_airport", type="string"),
     *                 @OA\Property(property="departure_iata", type="string"),
     *                 @OA\Property(property="departure_icao", type="string"),
     *                 @OA\Property(property="departure_terminal", type="string"),
     *                 @OA\Property(property="departure_gate", type="string"),
     *                 @OA\Property(property="departure_delay", type="integer"),
     *                 @OA\Property(property="departure_scheduled", type="string", format="date-time"),
     *                 @OA\Property(property="departure_estimated", type="string", format="date-time"),
     *                 @OA\Property(property="departure_actual", type="string", format="date-time"),
     *                 @OA\Property(property="arrival_airport", type="string"),
     *                 @OA\Property(property="arrival_iata", type="string"),
     *                 @OA\Property(property="arrival_icao", type="string"),
     *                 @OA\Property(property="arrival_terminal", type="string"),
     *                 @OA\Property(property="arrival_gate", type="string"),
     *                 @OA\Property(property="arrival_baggage", type="string"),
     *                 @OA\Property(property="arrival_delay", type="integer"),
     *                 @OA\Property(property="arrival_scheduled", type="string", format="date-time"),
     *                 @OA\Property(property="arrival_estimated", type="string", format="date-time"),
     *                 @OA\Property(property="arrival_actual", type="string", format="date-time"),
     *                 @OA\Property(property="airline_name", type="string"),
     *                 @OA\Property(property="airline_iata", type="string"),
     *                 @OA\Property(property="airline_icao", type="string"),
     *                 @OA\Property(property="flight_number", type="string"),
     *                 @OA\Property(property="flight_iata", type="string"),
     *                 @OA\Property(property="flight_icao", type="string"),
     *                 @OA\Property(property="flight_codeshared", type="boolean"),
     *                 @OA\Property(property="aircraft", type="string"),
     *                 @OA\Property(property="live", type="boolean")
     *             ))
     *         )
     *     )
     * )
     */
    public function getFlights(Request $request)
    {
        $data = $this->flightService->getFlights($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/flight-routes",
     *     summary="Get flight routes",
     *     description="Retrieve flight routes based on various filters such as flight number, departure/arrival airport codes, and airline codes.",
     *     tags={"Flight Routes"},
     *     @OA\Parameter(name="flight_number", in="query", description="Filter by flight number. Example: 2557", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="dep_iata", in="query", description="Filter by departure airport IATA code.", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="arr_iata", in="query", description="Filter by arrival airport IATA code.", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="dep_icao", in="query", description="Filter by departure airport ICAO code.", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="arr_icao", in="query", description="Filter by arrival airport ICAO code.", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="airline_iata", in="query", description="Filter by airline IATA code.", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="airline_icao", in="query", description="Filter by airline ICAO code.", required=false, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     )
     * )
     */
    public function getFlightRoutes(Request $request)
    {
        $data = $this->flightService->getFlightRoutes($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/airports",
     *     summary="Get a list of airports",
     *     description="Retrieve a list of airports with optional search functionality.",
     *     tags={"Airports"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to get autocomplete suggestions for airports",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of airports",
     *     )
     * )
     */
    public function getAirports(Request $request)
    {
        $data = $this->flightService->getAirports($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/airlines",
     *     summary="Get a list of airlines",
     *     description="Retrieve a list of airlines with optional search functionality.",
     *     tags={"Airlines"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to get autocomplete suggestions for airlines",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of airlines",
     *     )
     * )
     */
    public function getAirlines(Request $request)
    {
        $data = $this->flightService->getAirlines($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/airplanes",
     *     summary="Get a list of airplanes",
     *     description="Retrieve a list of airplanes with optional search functionality.",
     *     tags={"Airplanes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to get autocomplete suggestions for airplanes",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of airplanes",
     *     )
     * )
     */
    public function getAirplanes(Request $request)
    {
        $data = $this->flightService->getAirplanes($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/aircraft-types",
     *     summary="Get a list of aircraft types",
     *     description="Retrieve a list of aircraft types with optional search functionality.",
     *     tags={"Aircraft Types"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to get autocomplete suggestions for aircraft types",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of aircraft types",
     *     )
     * )
     */
    public function getAircraftTypes(Request $request)
    {
        $data = $this->flightService->getAircraftTypes($request);

        return response()->json($data, 200);
    }

    /**
     * Get a paginated list of cities with optional search filtering.
     *
     * @OA\Get(
     *     path="/api/mongo/cities",
     *     summary="Get a list of cities",
     *     description="Retrieve a paginated list of cities. Supports search for autocomplete suggestions.",
     *     tags={"Cities"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search for a city by name or IATA code",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     )
     * )
     */
    public function getCities(Request $request)
    {
        $data = $this->flightService->getCities($request);

        return response()->json($data, 200);
    }

    /**
     * Get a paginated list of countries with optional search filtering.
     *
     * @OA\Get(
     *     path="/api/mongo/countries",
     *     summary="Get a list of countries",
     *     description="Retrieve a paginated list of countries. Supports search for autocomplete suggestions.",
     *     tags={"Countries"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search for a country by name, ISO2, or ISO3 code",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function getCountries(Request $request)
    {
        $data = $this->flightService->getCountries($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/flight-schedules",
     *     summary="Get flight schedules",
     *     description="Retrieve flight schedules with optional filtering and pagination.",
     *     tags={"Flight Schedules"},
     *     @OA\Parameter(
     *         name="iataCode",
     *         in="query",
     *         description="The IATA code of the airport (e.g., JFK, DXB).",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Schedule type: departure or arrival.",
     *         required=true,
     *         @OA\Schema(type="string", enum={"departure", "arrival"})
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Flight status (e.g., landed, scheduled, cancelled, active, etc.).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="dep_terminal",
     *         in="query",
     *         description="Departure terminal number (e.g., 1, 2, 3, etc.).",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="dep_delay",
     *         in="query",
     *         description="Departure delay in minutes (e.g., 10, 20, etc.).",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="dep_schTime",
     *         in="query",
     *         description="Scheduled departure time (Format: YYYY-MM-DDTHH:MM:SS.000).",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="dep_estTime",
     *         in="query",
     *         description="Estimated departure time (Format: YYYY-MM-DDTHH:MM:SS.000).",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="dep_actTime",
     *         in="query",
     *         description="Actual departure time (Format: YYYY-MM-DDTHH:MM:SS.000).",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="arr_terminal",
     *         in="query",
     *         description="Arrival terminal number (e.g., 1, 2, 3, etc.).",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="arr_delay",
     *         in="query",
     *         description="Arrival delay in minutes (e.g., 10, 20, etc.).",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="arr_schTime",
     *         in="query",
     *         description="Scheduled arrival time (Format: YYYY-MM-DDTHH:MM:SS.000).",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="arr_estTime",
     *         in="query",
     *         description="Estimated arrival time (Format: YYYY-MM-DDTHH:MM:SS.000).",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="arr_actTime",
     *         in="query",
     *         description="Actual arrival time (Format: YYYY-MM-DDTHH:MM:SS.000).",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="airline_name",
     *         in="query",
     *         description="Airline name (e.g., Air France, Delta Air Lines).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="airline_iata",
     *         in="query",
     *         description="Airline IATA code (e.g., GB).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="airline_icao",
     *         in="query",
     *         description="Airline ICAO code (e.g., EIN).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="flight_num",
     *         in="query",
     *         description="Flight number (1 to 4 digits).",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="flight_iata",
     *         in="query",
     *         description="Flight IATA number (e.g., AA171).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="flight_icao",
     *         in="query",
     *         description="Flight ICAO number (e.g., EIN171).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         description="Language for translations (e.g., en, fr, de, etc.).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function getFlightSchedules(Request $request)
    {
        $data = $this->flightService->getFlightSchedules($request);

        return response()->json($data, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/mongo/flight-future-schedules",
     *     summary="Retrieve future flight schedules based on various filters",
     *     description="Fetch flight schedules based on airport IATA code, schedule type, date, and optional airline IATA/ICAO codes or flight number.",
     *     tags={"FlightSchedules"},
     *     @OA\Parameter(
     *         name="iataCode",
     *         in="query",
     *         required=true,
     *         description="The IATA code of the airport.",
     *         @OA\Schema(type="string", example="JFK")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=true,
     *         description="The schedule type: 'departure' or 'arrival'.",
     *         @OA\Schema(type="string", enum={"departure", "arrival"}, example="departure")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="The flight date in YYYY-MM-DD format.",
     *         @OA\Schema(type="string", format="date", example="2025-06-15")
     *     ),
     *     @OA\Parameter(
     *         name="airline_iata",
     *         in="query",
     *         required=false,
     *         description="Filter by airline IATA code.",
     *         @OA\Schema(type="string", example="AA")
     *     ),
     *     @OA\Parameter(
     *         name="airline_icao",
     *         in="query",
     *         required=false,
     *         description="Filter by airline ICAO code.",
     *         @OA\Schema(type="string", example="AAL")
     *     ),
     *     @OA\Parameter(
     *         name="flight_number",
     *         in="query",
     *         required=false,
     *         description="Filter by flight number.",
     *         @OA\Schema(type="string", example="100")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of results per page.",
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of future flight schedules.",
     *
     *     )
     * )
     */
    public function getFlightFutureSchedules(Request $request)
    {
        $data = $this->flightService->getFlightFutureSchedules($request);

        return response()->json($data, 200);
    }
}
