<?php

namespace Src\App\DTO;

readonly class ChangeEmailDto
{
    public function __construct(
        public string $id,
        public string $email,
        public string $password
    ) {
    }
}