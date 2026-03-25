<?php
namespace Src\Domain\User\Error;

class UnformattedPassword extends \Exception
{
    public function __construct(string $password)
    {
        parent::__construct("Invalid password format: $password");
    }
}