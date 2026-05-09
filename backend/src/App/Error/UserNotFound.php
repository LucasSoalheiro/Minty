<?php

namespace Src\App\Error;


class UserNotFound extends ApplicationError{
    // can be email or id
    public function __construct(string $string)
    {
        parent::__construct("User not found: $string");
    }
}