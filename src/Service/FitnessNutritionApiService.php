<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FitnessNutritionApiService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function fetchFitnessData()
    {
        try {
            // Actual API endpoint for fetching fitness data (replace with Nutritionix endpoint)
            $response = $this->client->request('GET', 'https://api.nutritionix.com/v1_1/exercise', [
                'query' => [
                    'appId' => '1b7c4652',
                    'appKey' => 'a6e13bc898ad5c905add8bba8e588492',
                ],
            ]);

            // Decode JSON response
            $data = $response->toArray();

            // Extract relevant data or return the entire response
            return $data;
        } catch (TransportExceptionInterface $e) {
            // Handle API request exception
            // For example: Log error, return empty array, throw custom exception, etc.
            return [];
        }
    }

    public function fetchNutritionData()
    {
        try {
            // Actual API endpoint for fetching nutrition data (replace with Nutritionix endpoint)
            $response = $this->client->request('GET', 'https://api.nutritionix.com/v1_1/search', [
                'query' => [
                    'appId' => '1b7c4652',
                    'appKey' => 'a6e13bc898ad5c905add8bba8e588492',
                ],
            ]);

            // Decode JSON response
            $data = $response->toArray();

            // Extract relevant data or return the entire response
            return $data;
        } catch (TransportExceptionInterface $e) {
            // Handle API request exception
            // For example: Log error, return empty array, throw custom exception, etc.
            return [];
        }
    }
}