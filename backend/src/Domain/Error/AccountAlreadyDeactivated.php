<?php

namespace Src\Domain\Error;

class AccountAlreadyDeactivated extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Account is already deactivated.");
    }
}