<?php

namespace App\Tests\Controller;

use App\Service\ExchangeRatesService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckoutControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $container = $this->client->getContainer();

        $exchangeRatesService = $this->createMock(ExchangeRatesService::class);
        $exchangeRatesService->expects($this->once())->method('getRates')->willReturn([
            'EURUSD' => 1.12,
            'EURCAD' => 1.32,
        ]);

        $container->set(ExchangeRatesService::class, $exchangeRatesService);
    }

    public function testCalculateCartTotal(): void
    {
        $payload = [
            'items' => [
                1 => ['price' => 100, 'currency' => 'USD', 'quantity' => 2],
                2 => ['price' => 50, 'currency' => 'EUR', 'quantity' => 1],
                3 => ['price' => 50, 'currency' => 'CAD', 'quantity' => 1],
            ],
            'checkoutCurrency' => 'EUR',
        ];

        $this->client->request('POST', '/api/v1/checkout', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals([
            'checkoutPrice' => 266.45,
            'checkoutCurrency' => 'EUR',
        ], $responseData);
    }
}
