<?php

namespace App\Core\User\UserInterface\Cli;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Application\Query\GetUsersByIsActiveStatus\GetUsersByIsActiveStatusQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:user:get-inactive',
    description: 'Get emails of inactive users.'
)]
class GetUsers extends Command {
    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->bus->dispatch(new GetUsersByIsActiveStatusQuery(false));

        $output->writeln([
            '',
            'Inactive Users Emails List',
            '==========================',
            ''
        ]);

        /** @var UserDTO $user */
        foreach ($users as $user) {
            $output->writeln($user->email);
        }

        $output->writeln([
            '',
            '==========================',
            ''
        ]);

        return Command::SUCCESS;
    }
}