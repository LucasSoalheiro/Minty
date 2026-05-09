<?php

namespace Src\App\Error;

class SessionNotFound extends ApplicationError
{
    public function __construct(string $session)
    {
        parent::__construct("Session not found with this token: $session");
    }
}