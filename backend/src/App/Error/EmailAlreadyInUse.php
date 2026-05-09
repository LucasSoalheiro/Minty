<?php

namespace Src\App\Error;

class EmailAlreadyInUse extends ApplicationError
{
    public function __construct(string $email)
    {
        parent::__construct("The email '$email' is already in use.");
    }
}