<?php

namespace Src\Domain\User\Error;

class EmailShouldBeDifferent extends \Exception
{
    public function __construct(string $email)
    {
        parent::__construct("Email should be different than the current one: $email");
    }
}