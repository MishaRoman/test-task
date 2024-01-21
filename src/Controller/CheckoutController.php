<?php

namespace App\Controller;

use App\Request\CalculateCartTotalRequest;
use App\Service\CalculateCartTotalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function __construct(private CalculateCartTotalService $calculateCartTotalService)
    {
    }

    #[Route(path: 'api/v1/checkout', methods: ['POST'])]
    public function calculateCartTotal(#[MapRequestPayload] CalculateCartTotalRequest $request): Response
    {
        $items = $request->items;
        $checkoutCurrency = $request->checkoutCurrency;

        $total = $this->calculateCartTotalService->calculate($items, $checkoutCurrency);

        return $this->json($total);
    }
}
