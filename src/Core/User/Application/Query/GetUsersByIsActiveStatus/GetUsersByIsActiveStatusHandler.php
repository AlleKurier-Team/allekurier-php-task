<?php

namespace App\Core\User\Application\Query\GetUsersByIsActiveStatus;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUsersByIsActiveStatusHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(GetUsersByIsActiveStatusQuery $query): array
    {
        $users = $this->userRepository->getByIsActiveStatus(
            $query->isActive,
        );

        return array_map(function (User $user) {
            return new UserDTO(
                $user->getId(),
                $user->getEmail(),
                $user->getIsActive(),
            );
        }, $users);
    }
}