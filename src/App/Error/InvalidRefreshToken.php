<?php

namespace Src\App\Error;

class InvalidRefreshToken extends ApplicationError
{
    public function __construct(string $refreshToken)
    {
        parent::__construct("Invalid refresh token: $refreshToken");
    }
}