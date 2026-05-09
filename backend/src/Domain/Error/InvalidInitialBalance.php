<?php

namespace Src\Domain\Error;

class InvalidInitialBalance extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Initial balance cannot be negative.");
    }
}