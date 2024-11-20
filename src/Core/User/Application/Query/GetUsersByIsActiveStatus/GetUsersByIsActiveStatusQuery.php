<?php

namespace App\Core\User\Application\Query\GetUsersByIsActiveStatus;

class GetUsersByIsActiveStatusQuery
{
    public function __construct(public readonly bool $isActive) {}
}
