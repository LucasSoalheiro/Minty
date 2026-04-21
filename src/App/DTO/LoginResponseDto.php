<?php

namespace Src\App\DTO;

readonly class LoginResponseDto
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken
    ) {
    }
}