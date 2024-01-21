<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class CalculateCartTotalRequest
{
    public function __construct(
        #[Type('array')]
        #[NotBlank()]
        public readonly array $items,

        #[NotBlank()]
        #[Length(exactly: 3, exactMessage: 'Currency code must be a 3 characters long')]
        public readonly string $checkoutCurrency
    ) {
    }
}
