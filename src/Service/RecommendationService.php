<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RecommendationService
{
    private $client;
    private $fastApiUrl;

    public function __construct(HttpClientInterface $client, string $fastApiUrl)
    {
        $this->client = $client;
        $this->fastApiUrl = $fastApiUrl;
    }

    public function getRecommendationsForGame(string $gameTitle): array
    {
        try {
            $safeTitle = rawurlencode($gameTitle);
            $response = $this->client->request(
                'GET',
                $this->fastApiUrl . $safeTitle
            );

            return $response->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
