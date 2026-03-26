<?php

namespace Src\Domain\Error;

class InvalidDescription extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Invalid description");
    }
}