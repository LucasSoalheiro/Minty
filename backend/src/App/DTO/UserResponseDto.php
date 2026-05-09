<?php

namespace Src\App\DTO;

readonly class UserResponseDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}