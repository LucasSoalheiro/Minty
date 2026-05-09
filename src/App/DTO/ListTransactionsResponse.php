<?php

namespace Src\App\DTO;

readonly class ListTransactionsResponse
{
    public function __construct(
        public string $id,
        public string $accountId,
        public int $amount,
        public string $createdAt,
        public string $type,
        public string $status,
        public ?string $description,
        public string $categoryId
    ) {
    }
}