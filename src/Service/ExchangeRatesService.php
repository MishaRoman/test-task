<?php

namespace App\Service;

use App\Interface\ExchangeRatesServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesService implements ExchangeRatesServiceInterface
{
    private string $host = 'http://api.exchangerate.host/live';

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function getRates(string $source, string $currencies): array
    {
        $params = [
            'query' => [
                'access_key' => $_ENV['EXCHANGE_RATES_API_KEY'],
                'source' => $source,
                'currencies' => $currencies,
            ],
        ];

        $response = $this->client->request('GET', $this->host, $params)->toArray();

        return $response['quotes'];
    }
}
