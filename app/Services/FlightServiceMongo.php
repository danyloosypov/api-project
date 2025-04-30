<?php

namespace App\Services;

use App\Repositories\Flights\FlightMongoRepositoryInterface;
use Illuminate\Http\Request;

class FlightServiceMongo
{
    protected $flightRepository;

    public function __construct(FlightMongoRepositoryInterface $flightRepository)
    {
        $this->flightRepository = $flightRepository;
    }

    public function getFlights(Request $request)
    {
        return $this->flightRepository->getFlights($request);
    }

    public function getFlightRoutes(Request $request)
    {
        return $this->flightRepository->getFlightRoutes($request);
    }

    public function getAirports(Request $request)
    {
        return $this->flightRepository->getAirports($request);
    }

    public function getAirlines(Request $request)
    {
        return $this->flightRepository->getAirlines($request);
    }

    public function getAirplanes(Request $request)
    {
        return $this->flightRepository->getAirplanes($request);
    }

    public function getAircraftTypes(Request $request)
    {
        return $this->flightRepository->getAircraftTypes($request);
    }

    public function getCities(Request $request)
    {
        return $this->flightRepository->getCities($request);
    }

    public function getCountries(Request $request)
    {
        return $this->flightRepository->getCountries($request);
    }

    public function getFlightSchedules(Request $request)
    {
        return $this->flightRepository->getFlightSchedules($request);
    }

    public function getFlightFutureSchedules(Request $request)
    {
        return $this->flightRepository->getFlightFutureSchedules($request);
    }

}
