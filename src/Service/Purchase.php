<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class Purchase implements PurchaseInterface
{
    protected $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    abstract public function makePurchase(array $cardholder_data): JsonResponse;
}