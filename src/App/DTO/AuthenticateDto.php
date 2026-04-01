<?php

namespace Src\App\DTO;

class AuthenticateDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }

}