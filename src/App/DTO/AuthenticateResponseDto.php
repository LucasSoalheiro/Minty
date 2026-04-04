<?php

namespace Src\App\DTO;

readonly class AuthenticateResponseDto
{
    public function __construct(
        public string $token,
        public string $userId
    ) {
    }
}