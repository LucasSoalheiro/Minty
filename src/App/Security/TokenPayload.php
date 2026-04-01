<?php

namespace Src\App\Security;

class TokenPayload
{
    public function __construct(
        public readonly string $userId,
        public readonly array $claims = []
    ) {
    }
}