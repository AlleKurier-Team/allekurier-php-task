<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use App\Core\User\Domain\Event\UserCreatedEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="users")
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer", options={"unsigned": true}, nullable=false)
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private bool $is_active = false;

    public function __construct(string $email)
    {
        $this->id = null;
        $this->email = $email;

        $this->record(new UserCreatedEvent($this));
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}
