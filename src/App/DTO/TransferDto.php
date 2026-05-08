<?php

namespace Src\App\DTO;

readonly class TransferDto
{
    public function __construct(
        public string $fromAccountId,
        public string $toAccountId,
        public int $amount,
        public string $categoryId,
        public ?string $description = null,
    ) {
    }
}