<?php

namespace App\Core\Invoice\Infrastructure\Notification\Email;

use App\Common\Mailer\SMTPMailer;
use App\Core\Invoice\Domain\Notification\NotificationInterface;

class Mailer implements NotificationInterface
{
    public function __construct(private readonly SMTPMailer $SMPTMailer)
    {
    }

    public function sendEmail(string $recipient, string $subject, string $message): void
    {
        $this->SMPTMailer->send($recipient, $subject, $message);
    }
}
