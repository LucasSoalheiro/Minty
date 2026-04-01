<?php

namespace Src\App\DTO;

class WithdrawDto
{
    public function __construct(
        public string $accountId,
        public float $amount,
        public string $categoryId,
        public ?string $description = null,
    ) {
    }
}