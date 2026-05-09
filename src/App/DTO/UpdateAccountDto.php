<?php

namespace Src\App\DTO;

readonly class UpdateAccountDto
{
    public function __construct(
        public string $accountId,
        public string $name
    ) {
    }
}