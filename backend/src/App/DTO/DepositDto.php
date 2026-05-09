<?php

namespace Src\App\DTO;

readonly class DepositDto
{
    public function __construct(
        public string $accountId,
        public int $amount,
        public string $categoryId,
        public ?string $description = null,
    ) {
    }

}