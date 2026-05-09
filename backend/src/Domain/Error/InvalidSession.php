<?php

namespace Src\Domain\Error;

use DomainException;

class InvalidSession extends DomainException
{
    public function __construct(?string $message)
    {
        parent::__construct($message || "Session is Invalid");
    }
}