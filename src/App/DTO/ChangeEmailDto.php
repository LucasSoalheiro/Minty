<?php

namespace Src\App\DTO;

class ChangeEmailDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}