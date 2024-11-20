<?php

namespace App\Core\User\Application\Command\GetInactiveUsersEmails;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetInactiveUsersEmailsHandler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(GetInactiveUsersEmailsCommand $command): array
    {
        $users = $this->userRepository->getInactive();

        return array_map(function (User $user) {
            return new UserDTO(
                $user->getEmail(),
                $user->isActive()
            );
        }, $users);
    }
}
