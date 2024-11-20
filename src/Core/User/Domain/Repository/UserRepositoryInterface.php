<?php

namespace App\Core\User\Domain\Repository;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\User;

interface UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): User;

    /**
     * @param bool $isActive
     * @return User[]
     */
    public function getByIsActiveStatus(bool $isActive): array;

    public function save(User $user): void;

    public function flush(): void;
}
