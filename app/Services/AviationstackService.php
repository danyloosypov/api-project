<?php

namespace App\Services;

use Exception;

class AviationstackService
{
    protected $apiUrl = 'https://api.aviationstack.com/v1';
    private $key = '';

    public function __construct()
    {
        // Get the API key from the config
        $this->key = config('services.aviationstack.key');
    }

    /**
     * Fetch data from the Aviationstack API for a specific endpoint.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws Exception
     */
    private function fetchDataFromApi(string $endpoint, array $params = []): array
    {
        // Build query string with API key and additional parameters
        $queryString = http_build_query(array_merge(['access_key' => $this->key], $params));

        // Make the API request
        try {
            $response = $this->makeRequest($endpoint, $queryString);

            // Decode the JSON response
            $apiResult = json_decode($response, true);

            // Check if the API response contains an error
            if (isset($apiResult['error'])) {
                throw new Exception($apiResult['error']['message']);
            }

            return $apiResult;
        } catch (Exception $e) {
            // Handle and log the error if needed
            Log::error('Error fetching: ' . $endpoint);
            Log::error('Aviationstack API error: ' . $e->getMessage());

            // Return an empty array on failure
            return [];
        }
    }

    /**
     * Fetch aircraft types data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchAircraftTypesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/aircraft_types', $params);
    }

    /**
     * Fetch airlines data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchAirlinesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/airlines', $params);
    }

    /**
     * Fetch airplanes data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchAirplanesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/airplanes', $params);
    }

    /**
     * Fetch airports data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchAirportsData(array $params = []): array
    {
        return $this->fetchDataFromApi('/airports', $params);
    }

    /**
     * Fetch cities data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchCitiesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/cities', $params);
    }

    /**
     * Fetch countries data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchCountriesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/countries', $params);
    }

    /**
     * Fetch flights data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchFlightsData(array $params = []): array
    {
        return $this->fetchDataFromApi('/flights', $params);
    }

    /**
     * Fetch FlightFutureSchedules data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchFlightFutureSchedulesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/flightsFuture', $params);
    }

    /**
     * Fetch routes data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchFlightRoutesData(array $params = []): array
    {
        return $this->fetchDataFromApi('/routes', $params);
    }

    /**
     * Fetch timetable data from the Aviationstack API
     *
     * @param array $params
     * @return array
     */
    public function fetchFlightScheduleData(array $params = []): array
    {
        return $this->fetchDataFromApi('/timetable', $params);
    }

    /**
     * Make a request to the Aviationstack API
     *
     * @param string $endpoint
     * @param string $queryString
     * @return string
     * @throws Exception
     */
    private function makeRequest(string $endpoint, string $queryString): string
    {
        // Build the full URL for the request
        $url = sprintf('%s%s?%s', $this->apiUrl, $endpoint, $queryString);

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($ch);

        // Check if there was a cURL error
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        // Close the cURL session
        curl_close($ch);

        return $response;
    }
}
