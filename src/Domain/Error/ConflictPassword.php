<?php
namespace Src\Domain\Error;
class ConflictPassword extends \DomainException
{
    public function __construct(string $message = "Password Conflict")
    {
        parent::__construct($message);
    }
}