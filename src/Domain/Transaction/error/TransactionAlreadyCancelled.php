<?php

namespace Src\Domain\Transaction\error;

class TransactionAlreadyCancelled extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Transaction is already cancelled.");
    }
}