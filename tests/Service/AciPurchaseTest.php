<?php

namespace App\Tests\Service;

use App\Service\PurchaseInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class AciPurchaseTest extends KernelTestCase
{
    public function testPurchaseCreation(): void
    {
        $kernel = self::bootKernel();

        $aciPurchaseService = static::getContainer()->get('app.aci_purchase');
        assert($aciPurchaseService instanceof PurchaseInterface);

        $cardholder_data = [
            'amount' => 9.99,
            'currency' => 'EUR',
            'card_number' => '4242424242424242',
            'card_exp_month' => '12',
            'card_exp_year' => '2034',
            'card_cvv' => '123',
        ];

        $response = $aciPurchaseService->makePurchase($cardholder_data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertMatchesRegularExpression('/{"transaction_id":".+","created":".+","amount":.+,"currency":"EUR","card_bin":"\d{6}"}/', $response->getContent());
    }
}
