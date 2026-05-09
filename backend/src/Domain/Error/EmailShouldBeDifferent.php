<?php

namespace Src\Domain\Error;

class EmailShouldBeDifferent extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Email should be different than the current one: $email");
    }
}