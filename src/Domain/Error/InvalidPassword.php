<?php
namespace Src\Domain\Error;
class InvalidPassword extends \DomainException
{
    public function __construct(string $message = "Invalid password")
    {
        parent::__construct($message);
    }
}