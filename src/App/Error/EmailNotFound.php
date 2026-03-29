<?php

namespace Src\App\Error;

class EmailNotFound extends ApplicationError
{
    public function __construct(string $email)
    {
        parent::__construct("Email not found: $email");
    }
}