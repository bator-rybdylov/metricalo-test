<?php

namespace App\Controller;

use App\Service\PurchaseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PurchaseController extends AbstractController
{
    private $purchaseServiceLocator;

    private $logger;

    public function __construct(ServiceLocator $purchaseServiceLocator, LoggerInterface $logger)
    {
        $this->purchaseServiceLocator = $purchaseServiceLocator;
        $this->logger = $logger;
    }

    #[Route('/app/example/{payment_gateway}', requirements: ['payment_gateway' => 'aci|shift4'], name: 'app_purchase', methods: ['POST'])]
    public function index(string $payment_gateway, Request $request): JsonResponse
    {
        /** @var PurchaseInterface $purchase_service */
        $purchase_service = $this->purchaseServiceLocator->get($payment_gateway);

        $cardholder_data = [
            'amount' => $request->get('amount'),
            'currency' => $request->get('currency'),
            'card_number' => $request->get('card_number'),
            'card_exp_month' => $request->get('card_exp_month'),
            'card_exp_year' => $request->get('card_exp_year'),
            'card_cvv' => $request->get('card_cvv'),
        ];

        return $purchase_service->makePurchase($cardholder_data);
    }
}
