<?php
namespace Src\Domain\User\Error;

class InvalidEmail extends \Exception
{
    public function __construct(string $email)
    {
        parent::__construct("Invalid email format: $email");
    }
}