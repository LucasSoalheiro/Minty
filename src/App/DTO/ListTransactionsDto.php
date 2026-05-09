<?php

namespace Src\App\DTO;

readonly class ListTransactionsDto
{
    public function __construct(
        public string $accountId,
        public ?string $status = null
    ) {
    }
}