<?php

namespace Src\App\Error;


class UserNotFound extends ApplicationError{
    public function __construct(string $id)
    {
        parent::__construct("User not found with id: $id");
    }
}