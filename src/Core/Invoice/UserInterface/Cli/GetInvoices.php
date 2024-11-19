<?php

namespace App\Core\Invoice\UserInterface\Cli;

use App\Common\Bus\QueryBusInterface;
use App\Core\Invoice\Application\DTO\InvoiceDTO;
use App\Core\Invoice\Application\Query\GetInvoicesByStatusAndAmountGreater\GetInvoicesByStatusAndAmountGreaterQuery;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use App\Core\Invoice\Domain\ValueObject\Amount;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:invoice:get-by-status-and-amount',
    description: 'Pobieranie identyfikatorów faktur dla wybranego statusu i kwot większych od'
)]
class GetInvoices extends Command
{
    public function __construct(private readonly QueryBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var InvoiceDTO $invoice */
        $invoices = $this->bus->dispatch(new GetInvoicesByStatusAndAmountGreaterQuery(
            InvoiceStatus::from($input->getArgument('status')),
            new Amount((int) $input->getArgument('amount')),
        ));

        $this->displayResult($output, ...$invoices);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('status', InputArgument::REQUIRED);
        $this->addArgument('amount', InputArgument::REQUIRED);
    }

    private function displayResult(OutputInterface $output, InvoiceDTO ...$invoices): void
    {
        foreach ($invoices as $invoice) {
            $output->writeln($invoice->id->toRfc4122());
        }
    }
}
