<?php

namespace App\Repositories\Flights;

use Illuminate\Http\Request;

interface FlightRepositoryInterface
{
    public function getFlights(Request $request);
    public function getFlightRoutes(Request $request);
    public function getAirports(Request $request);
    public function getAirlines(Request $request);
    public function getAirplanes(Request $request);
    public function getAircraftTypes(Request $request);
    public function getCities(Request $request);
    public function getCountries(Request $request);
    public function getFlightSchedules(Request $request);
    public function getFlightFutureSchedules(Request $request);
}
