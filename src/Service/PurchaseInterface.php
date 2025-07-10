<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;

interface PurchaseInterface
{
    public function makePurchase(array $cardholder_data): JsonResponse;
}