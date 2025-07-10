<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class Shift4Purchase extends Purchase
{
    private const API_URL = 'https://api.shift4.com/charges';

    public function makePurchase(array $cardholder_data): JsonResponse
    {
        // Request parameters for API call.
        $purchase_request_params = [
            'amount' => 100 * floatval($cardholder_data['amount']),
            'currency' => $cardholder_data['currency'],
            'card.number' => $cardholder_data['card_number'],
            'card.expMonth' => $cardholder_data['card_exp_month'],
            'card.expYear' => $cardholder_data['card_exp_year'],
            'card.cvc' => $cardholder_data['card_cvv'],
        ];

        try {
            // Auth key is hardcoded due to the task description
            $response = $this->httpClient->request('POST', self::API_URL, [
                'auth_basic' => ['sk_test_kQCsbCDC2sGUYBFF1BieOQfM', ''],
                'body' => $purchase_request_params,
            ]);
            $response = $response->toArray();
        } catch (ExceptionInterface $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ]);
        }

        $created = date('Y-m-d H:i:s.vO', $response['created']);
        $response['created'] = $created;

        return new JsonResponse([
            'transaction_id' => $response['id'],
            'created' => $response['created'],
            'amount' => floatval($response['amount']) / 100,
            'currency' => $response['currency'],
            'card_bin' => $response['card']['first6'],
        ]);
    }
}