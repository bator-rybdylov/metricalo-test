<?php

namespace App\Command;

use App\Service\PurchaseInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:example',
    description: 'Create a new charge depending on the payment gateway',
)]
class PurchaseCommand extends Command
{
    private $purchaseServiceLocator;

    public function __construct($purchaseServiceLocator)
    {
        parent::__construct();

        $this->purchaseServiceLocator = $purchaseServiceLocator;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('payment_gateway', InputArgument::REQUIRED, 'Possible values: "aci" or "shift4"')
            ->addArgument('amount', InputArgument::REQUIRED, 'E.g. 12.59')
            ->addArgument('currency', InputArgument::REQUIRED, 'E.g. EUR')
            ->addArgument('card_number', InputArgument::REQUIRED, 'E.g. 4242424242424242')
            ->addArgument('card_exp_month', InputArgument::REQUIRED, 'E.g. 07')
            ->addArgument('card_exp_year', InputArgument::REQUIRED, 'E.g. 2026')
            ->addArgument('card_cvv', InputArgument::REQUIRED, 'E.g. 123')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $payment_gateway = $input->getArgument('payment_gateway');

        if ($payment_gateway) {
            $cardholder_data = [
                'amount' => $input->getArgument('amount'),
                'currency' => $input->getArgument('currency'),
                'card_number' => $input->getArgument('card_number'),
                'card_exp_month' => $input->getArgument('card_exp_month'),
                'card_exp_year' => $input->getArgument('card_exp_year'),
                'card_cvv' => $input->getArgument('card_cvv'),
            ];

            /** @var PurchaseInterface $purchase_service */
            $purchase_service = $this->purchaseServiceLocator->get($payment_gateway);

            $io->note(sprintf('Response: %s', $purchase_service->makePurchase($cardholder_data)));
        }

        return Command::SUCCESS;
    }
}
