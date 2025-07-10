<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class AciPurchase extends Purchase
{
    private const API_URL = 'https://eu-test.oppwa.com/v1/payments';

    public function makePurchase(array $cardholder_data): JsonResponse
    {
        // Request parameters for API call. Some of them are hardcoded
        // because they are limited to the test mode
        $purchase_request_params = [
            'entityId' => '8ac7a4c79394bdc801939736f17e063d',
            'amount' => floatval($cardholder_data['amount']),
            'currency' => 'EUR',
            'paymentBrand' => 'VISA',
            'paymentType' => 'DB',
            'card.number' => '4200000000000000',
            'card.holder' => 'Jane Jones',
            'card.expiryMonth' => $cardholder_data['card_exp_month'],
            'card.expiryYear' => $cardholder_data['card_exp_year'],
            'card.cvv' => $cardholder_data['card_cvv'],
        ];

        try {
            // Auth key is hardcoded due to the task description
            $response = $this->httpClient->request('POST', self::API_URL, [
                'auth_bearer' => 'OGFjN2E0Yzc5Mzk0YmRjODAxOTM5NzM2ZjFhNzA2NDF8enlac1lYckc4QXk6bjYzI1NHNng=',
                'body' => $purchase_request_params,
            ]);
            $response = $response->toArray();
        } catch (ExceptionInterface $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ]);
        }

        return new JsonResponse([
            'transaction_id' => $response['id'],
            'created' => $response['timestamp'],
            'amount' => floatval($response['amount']),
            'currency' => $response['currency'],
            'card_bin' => $response['card']['bin'],
        ]);
    }
}