<?php

namespace App\Interface;

interface ExchangeRatesServiceInterface
{
    public function getRates(string $source, string $currencies): array;
}
