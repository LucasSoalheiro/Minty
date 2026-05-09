<?php

namespace Src\App\DTO;

readonly class CreateUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }

}