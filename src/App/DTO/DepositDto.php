<?php

namespace Src\App\DTO;

class DepositDto
{
    public function __construct(
        public string $accountId,
        public float $amount,
        public string $categoryId,
        public ?string $description = null,
    ) {
    }

}