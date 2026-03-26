<?php
namespace Src\Domain\Error;
class InvalidPassword extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Invalid password");
    }
}