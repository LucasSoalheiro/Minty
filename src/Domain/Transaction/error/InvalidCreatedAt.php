<?php

namespace Src\Domain\Transaction\error;

class InvalidCreatedAt extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("CreatedAt must be a valid DateTime object.");
    }
}