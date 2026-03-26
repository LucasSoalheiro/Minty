<?php

namespace Src\Domain\Transaction\error;

class InvalidAmount extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Amount must be greater than zero.");
    }
}