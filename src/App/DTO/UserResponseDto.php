<?php

namespace Src\App\DTO;

class UserResponseDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}