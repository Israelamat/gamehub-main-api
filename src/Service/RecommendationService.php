<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecommendationService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRecommendationsForGame(string $gameTitle): array
    {
        try {
            $safeTitle = rawurlencode($gameTitle);
            $response = $this->client->request(
                'GET',
                'http://127.0.0.1:8001/recommend/' . $safeTitle
            );

            return $response->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
