<?php
namespace Src\Domain\Error;

class InvalidEmail extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Invalid email format: $email");
    }
}