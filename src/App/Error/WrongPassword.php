<?php

namespace Src\App\Error;

class WrongPassword extends ApplicationError
{
    public function __construct(string $message = "Wrong password")
    {
        parent::__construct($message);
    }
}