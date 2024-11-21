<?php

namespace App\Core\User\Domain\ValueObject;

use App\Core\User\Domain\Exception\UserException;

class Email
{
    private string $email;

    private const EMAIL_REGEX = '/^\S+\@\S+\.\w{2,}$/';

    public function __construct(string $email)
    {
        if (!preg_match(self::EMAIL_REGEX, $email)) {
            throw new UserException('Invalid email format. Must match '.self::EMAIL_REGEX);
        }

        $this->email = $email;
    }

    public function getValue(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
