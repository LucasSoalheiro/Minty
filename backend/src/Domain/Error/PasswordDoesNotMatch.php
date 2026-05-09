<?php

namespace Src\Domain\Error;

class PasswordDoesNotMatch extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Password does not match with the original");
    }
}