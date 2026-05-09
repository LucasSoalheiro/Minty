<?php

namespace Src\Domain\Error;

class InvalidCreatedAt extends \DomainException
{
    public function __construct()
    {
        parent::__construct("CreatedAt must be a valid DateTime object.");
    }
}