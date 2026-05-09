<?php

namespace Src\App\DTO;

readonly class RefreshTokenResponse{
    public function __construct(
        public string $accessToken,
        public string $refreshToken
    )
    {
    }
}