<?php

namespace App\Services;

use App\Models\Price;
use Illuminate\Support\Facades\Http;
// Models
use Illuminate\Support\Facades\Log;

class AlphaVantageService
{
    // API URL for the Alpha Vantage API
    const API_URL = 'https://www.alphavantage.co/query';

    /* Available AlphaVantage stock functions */

    // https://www.alphavantage.co/documentation/#intraday
    const TIME_SERIES_INTRADAY = 'TIME_SERIES_INTRADAY';

    // https://www.alphavantage.co/documentation/#daily
    const TIME_SERIES_DAILY = 'TIME_SERIES_DAILY';

    // ...
    private $availableFunctions = [
        self::TIME_SERIES_INTRADAY,
        self::TIME_SERIES_DAILY,
    ];

    // API key for the Alpha Vantage API
    private $apiKey;

    // Constructor to initialize the API key
    public function __construct()
    {
        $this->apiKey = config('alphavantage.api_key');
    }

    /**
     * TIME_SERIES_INTRADAY
     *
     * @param  string  $symbol  Single stock symbol
     * @param  array  $params  Additional API parameters
     * @return object Decoded API object
     */
    public function intraday($symbol, $params = [])
    {
        // Unfortunately, the Alpha Vantage API does not support enough parameters or their order in 'demo' model
        // Not able to use  extra Params
        try {
            // Make HTTP request to Alpha Vantage API
            $response = Http::get(self::API_URL, [
                'function' => self::TIME_SERIES_INTRADAY,
                'symbol' => $symbol,
                'interval' => $params['interval'] ?? '1min',
                'apikey' => $this->apiKey,
            ]);
            // Check if the request was successful
            if ($response->successful()) {
                return $this->transformResponse($response->json(), $params['interval'] ?? '1min');
            } else {
                // Log unsuccessful API response
                Log::error('API request failed: '.$response->status());

                return null;
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur during the API request
            Log::error('Error fetching data from API: '.$e->getMessage());

            return null;
        }
    }

    /**
     * TIME_SERIES_DAILY
     *
     * @param  string  $symbol  Single stock symbol
     * @param  array  $params  Additional API parameters
     * @return object Decoded API object
     */
    public function daily($symbol, $params = [])
    {
        // Unfortunately, the Alpha Vantage API does not support enough parameters or their order in 'demo' model
        // Not able to use  extra Params
        try {
            // Make HTTP request to Alpha Vantage API
            $response = Http::get(self::API_URL, [
                'function' => self::TIME_SERIES_DAILY,
                'symbol' => $symbol,
                'apikey' => $this->apiKey,
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                return $this->transformResponse($response->json(), 'Daily');
            } else {
                // Log unsuccessful API response
                Log::error('API request failed: '.$response->status());

                return null;
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur during the API request
            Log::error('Error fetching data from API: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Transforms the API response
     *
     * @param  array  $response  Decoded API response
     * @return array Transformed API response
     */
    private function transformResponse($response, $interval = '1min')
    {

        // Check if the response is an array
        if (is_array($response)) {

            // Check if the response contains a 'Time Series ($interval)' key
            if (array_key_exists("Time Series ($interval)", $response)) {

                // Get the 'Time Series ($interval)' key
                $timeSeries = $response["Time Series ($interval)"];

                try {
                    // Create a new Price object for each time series entry
                    return collect($timeSeries)->map(function ($price, $datetime) {
                        return new Price([
                            'datetime' => $datetime,
                            'open' => $price['1. open'],
                            'high' => $price['2. high'],
                            'low' => $price['3. low'],
                            'close' => $price['4. close'],
                            'volume' => $price['5. volume'],
                        ]);
                    });
                } catch (\Exception $e) {
                    // Log any exceptions that occur during the transformation
                    Log::error('Error transforming data: '.$e->getMessage());

                    return null;
                }
            }

            // Is it Rate Limiting ??
            elseif (array_key_exists('Note', $response)) {
                Log::error('API request rate limit: '.$response['Note']);

                return null;
            }
            // Is it Error Message ??
            elseif (array_key_exists('Error Message', $response)) {
                Log::error('API request error: '.$response['Error Message']);

                return null;
            }
            // Is it Information ??
            elseif (array_key_exists('Information', $response)) {
                Log::error('API request information: '.$response['Information']);

                return null;
            }
        }
        Log::error('Invalid API response: '.print_r($response, true));

        return null;
    }

    /**
     * Checks if the function name is available
     *
     * @param  string  $name  Name of the Alpha Vantage function to execute
     * @return bool
     */
    protected function funcAvailable($name)
    {
        return in_array($name, $this->availableFunctions);
    }
}
