<?php

namespace App\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateInvoiceHandler
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(CreateInvoiceCommand $command): void
    {
        $invoice = new Invoice(
            $this->userRepository->getActiveByEmail($command->email),
            $command->amount,
        );

        $this->invoiceRepository->save($invoice);

        $this->invoiceRepository->flush();
    }
}
