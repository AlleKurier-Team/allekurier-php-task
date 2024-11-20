<?php

declare(strict_types=1);

namespace App\Core\User\UserInterface\Cli;

use App\Common\Bus\CommandBusInterface;
use App\Core\Common\Domain\ValueObject\Email;
use App\Core\User\Application\Command\CreateUser\CreateUserCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'app:user:create',
    description: 'Dodawanie nowego użytkownika'
)]
final class CreateUser extends Command
{
    public function __construct(private readonly CommandBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        try {
            $email = new Email($input->getArgument('email'));

            $id = new Ulid();

            $this->bus->dispatch(new CreateUserCommand($id, $email));

            $style->success(sprintf('A new user has been created. ID: %s.', $id->toRfc4122()));

            return Command::SUCCESS;
        } catch (\DomainException $e) {
            $style->warning($e->getMessage());

            return Command::FAILURE;
        }
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
    }
}
