<?php

namespace Src\App\DTO;

readonly class LoginDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }

}