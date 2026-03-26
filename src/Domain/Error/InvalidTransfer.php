<?php

namespace Src\Domain\Error;

class InvalidTransfer extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Invalid transfer: source and/or destination accounts must be different.");
    }
}