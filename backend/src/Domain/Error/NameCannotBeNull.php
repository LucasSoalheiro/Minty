<?php

namespace Src\Domain\Error;

class NameCannotBeNull extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Name cannot be null");
    }
}