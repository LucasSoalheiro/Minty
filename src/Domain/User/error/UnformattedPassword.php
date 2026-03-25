<?php
namespace Src\Domain\User\error;

class UnformattedPassword extends \Exception
{
    public function __construct(string $password)
    {
        parent::__construct("Invalid password format: $password");
    }
}