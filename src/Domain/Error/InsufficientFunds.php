<?php

namespace Src\Domain\Error;
class InsufficientFunds extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Insufficient funds in the account.");
    }
}