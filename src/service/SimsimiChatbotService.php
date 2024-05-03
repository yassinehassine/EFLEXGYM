<?php

namespace App\service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SimsimiChatbotService
{
    private $httpClient;
    private $apiKey;
    private $apiUrl = 'https://wsapi.simsimi.com/190410/talk';

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function sendMessage(string $message): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->apiKey,
            ],
            'json' => [
                'utext' => $message,
            ],
        ]);

        $data = $response->toArray();

        return $data['atext'] ?? 'Sorry, I cannot respond to that.';
    }
}
