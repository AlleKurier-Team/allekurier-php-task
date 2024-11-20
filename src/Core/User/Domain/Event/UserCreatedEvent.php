<?php

namespace App\Core\User\Domain\Event;

class UserCreatedEvent extends AbstractUserEvent
{
    public function getUserEmail(): string
    {
        return $this->user->getEmail();
    }
}