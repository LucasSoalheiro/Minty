<?php

namespace Src\App\DTO;

class TransferDto
{
    public function __construct(
        public string $fromAccountId,
        public string $toAccountId,
        public float $amount,
        public string $categoryId,
        public ?string $description = null,
    ) {
    }
}