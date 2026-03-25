<?php

namespace Src\Domain\User\error;
class WeakPassword extends \Exception
{
    public function __construct(string $password)
    {
        parent::__construct("Weak password: $password. Password must be at least 8 characters long and contain a mix of letters, numbers, and special characters.");
    }
}