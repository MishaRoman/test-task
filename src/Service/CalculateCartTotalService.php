<?php

namespace App\Service;

use App\Interface\ExchangeRatesServiceInterface;

class CalculateCartTotalService
{
    public function __construct(private ExchangeRatesServiceInterface $exchangeRatesService)
    {
    }

    public function calculate(array $items, string $checkoutCurrency): array
    {
        $total = 0;
        $currencies = $this->getCurrenciesOfItems($items);

        $rates = $this->exchangeRatesService->getRates($checkoutCurrency, $currencies);

        foreach ($items as $itemId => $item) {
            $exchangeRateCode = $checkoutCurrency.$item['currency'];

            $rate = $item['currency'] === $checkoutCurrency ? 1 : $rates[$exchangeRateCode];
            $total += ($item['price'] / $rate) * $item['quantity'];
        }

        return [
            'checkoutPrice' => round($total, 2),
            'checkoutCurrency' => $checkoutCurrency,
        ];
    }

    private function getCurrenciesOfItems(array $items): string
    {
        $currencies = [];

        foreach ($items as $itemId => $item) {
            $currencies[] = $item['currency'];
        }

        $currencies = implode(',', $currencies);

        return $currencies;
    }
}
