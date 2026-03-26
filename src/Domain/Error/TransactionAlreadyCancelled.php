<?php

namespace Src\Domain\Error;

class TransactionAlreadyCancelled extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Transaction is already cancelled.");
    }
}