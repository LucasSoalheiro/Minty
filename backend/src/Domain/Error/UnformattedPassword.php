<?php
namespace Src\Domain\Error;
class UnformattedPassword extends \DomainException
{
    public function __construct(string $password)
    {
        parent::__construct("Invalid password format: $password");
    }
}