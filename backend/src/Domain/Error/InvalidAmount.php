<?php

namespace Src\Domain\Error;

class InvalidAmount extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Amount must be greater than zero.");
    }
}