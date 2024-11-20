<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"email"}, message="This Email is in use already")
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="email", type="string", length=300, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(message="The email {{ value }} is not a valid email.", normalizer="trim")
     */
    private string $email;

    /**
     * @ORM\Column(type=Types::BOOLEAN, options={"default"=false}, nullable=false)
     */
    private string $isActive;

    public function __construct(string $email)
    {
        $this->id = null;
        $this->email = $email;
        $this->isActive = false;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
