<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\Exception\UserExistsException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ValidatorInterface $validator
    ) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $user = new User($command->email);
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new UserExistsException();
        }

        $this->userRepository->save(new User(
            $command->email
        ));

        $this->userRepository->flush();
    }
}