<?php

namespace App\Tests\Service;

use App\Interface\ExchangeRatesServiceInterface;
use App\Service\CalculateCartTotalService;
use PHPUnit\Framework\TestCase;

class CalculateCartTotalServiceTest extends TestCase
{
    public function testCalculate(): void
    {
        $exchangeRatesService = $this->createMock(ExchangeRatesServiceInterface::class);
        $exchangeRatesService->method('getRates')->willReturn([
            'EURUSD' => 1.12,
            'EURCAD' => 1.32,
        ]);

        $calculateService = new CalculateCartTotalService($exchangeRatesService);

        $items = [
            1 => ['price' => 100, 'currency' => 'USD', 'quantity' => 2],
            2 => ['price' => 50, 'currency' => 'EUR', 'quantity' => 1],
            3 => ['price' => 50, 'currency' => 'CAD', 'quantity' => 1],
        ];

        $result = $calculateService->calculate($items, 'EUR');

        $this->assertEquals([
            'checkoutPrice' => 266.45,
            'checkoutCurrency' => 'EUR',
        ], $result);
    }
}
