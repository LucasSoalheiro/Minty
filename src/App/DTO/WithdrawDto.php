<?php

namespace Src\App\DTO;

class WithdrawDto
{
    public function __construct(
        private string $accountId,
        private float $amount,
        private string $categoryId,
        private ?string $description = null,
    ) {
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}