<?php

namespace Src\App\DTO;

readonly class CreateAccountDto
{
    public function __construct(
        public string $name,
        public int $balance,
        public string $userId
    ) {
    }
}