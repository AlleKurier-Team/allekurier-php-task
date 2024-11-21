<?php

namespace App\Core\Invoice\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use App\Core\Invoice\Domain\Event\InvoiceCanceledEvent;
use App\Core\Invoice\Domain\Event\InvoiceCreatedEvent;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use App\Core\User\Domain\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="invoices")
 */
class Invoice
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
     * @ORM\ManyToOne(targetEntity="App\Core\User\Domain\User")
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true}, nullable=false)
     */
    private int $amount;

    /**
     * @ORM\Column(type="string", length=16, nullable=false, enumType="\App\Core\Invoice\Domain\Status\InvoiceStatus")
     */
    private InvoiceStatus $status;

    public function __construct(User $user, int $amount)
    {
        if ($amount <= 0) {
            throw new InvoiceException('Kwota faktury musi być większa od 0');
        }

        if (!$user->isActive()) {
            throw new InvoiceException('Cannot create invoice for inactive user');
        }

        $this->id = null;
        $this->user = $user;
        $this->amount = $amount;
        $this->status = InvoiceStatus::NEW;

        $this->record(new InvoiceCreatedEvent($this));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function cancel(): void
    {
        $this->status = InvoiceStatus::CANCELED;
        $this->record(new InvoiceCanceledEvent($this));
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
