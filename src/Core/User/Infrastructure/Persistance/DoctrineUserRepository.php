<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\Exception\ActiveUserNotFoundException;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        return $user;
    }

    public function getByIsActiveStatus(bool $isActive): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.isActive = :is_active')
            ->setParameter('is_active', $isActive)
            ->getQuery()
            ->getResult();
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function getActiveByEmail(string $email): User
    {
        $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['email' => $email, 'isActive' => true]);

        if (null === $user) {
            throw new ActiveUserNotFoundException('Aktywny użytkownik nie istnieje');
        }

        return $user;
    }
}
