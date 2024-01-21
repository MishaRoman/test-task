<?php

namespace App\Tests\Service;

use App\Service\ExchangeRatesService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeRatesServiceTest extends TestCase
{
    private ExchangeRatesService $exchangeRatesService;
    private HttpClientInterface $httpClientMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->exchangeRatesService = new ExchangeRatesService($this->httpClientMock);
    }

    public function testGetRates(): void
    {
        $source = 'EUR';
        $currencies = 'USD,CAD';

        $apiResponse = [
            'quotes' => [
                'EURUSD' => 0.85,
                'EURCAD' => 0.75,
            ],
        ];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn($apiResponse);

        $this->httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'http://api.exchangerate.host/live',
                [
                    'query' => [
                        'access_key' => $_ENV['EXCHANGE_RATES_API_KEY'],
                        'source' => $source,
                        'currencies' => $currencies,
                    ],
                ]
            )
            ->willReturn($responseMock);

        $result = $this->exchangeRatesService->getRates($source, $currencies);

        $this->assertEquals($apiResponse['quotes'], $result);
    }
}
