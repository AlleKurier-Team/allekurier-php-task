<?php

namespace App\Core\User\UserInterface\Cli;

use App\Core\User\Application\Command\CreateUser\CreateUserCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsCommand(
    name: 'app:user:create',
    description: 'Dodawanie nowego użytkownika'
)]
class CreateUser extends Command
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '',
            'User Creator',
            '============',
            '',
        ]);

        $email = $input->getArgument('email');
        try {
            $this->bus->dispatch(
                new CreateUserCommand(
                    $email,
                ),
            );

            $output->writeln([
                sprintf('User with email "%s" successfully created', $email),
                '',
                '============',
                '',
            ]);

            return Command::SUCCESS;
        } catch (Throwable) {
            $output->writeln([
                sprintf('User with email address "%s" could not be created', $email),
                '',
                '============',
                '',
            ]);

            return Command::FAILURE;
        }
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
    }
}