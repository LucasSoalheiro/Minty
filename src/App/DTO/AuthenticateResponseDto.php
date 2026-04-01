<?php

namespace Src\App\DTO;

class AuthenticateResponseDto
{
    public function __construct(
        public string $token,
        public string $userId
    ) {
    }
}