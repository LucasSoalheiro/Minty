<?php

namespace Src\App\DTO;

class TransferDto
{
    public function __construct(
        private string $fromAccountId,
        private string $toAccountId,
        private float $amount,
        private string $categoryId,
        private ?string $description = null,
    ) {
    }

    public function getFromAccountId(): string
    {
        return $this->fromAccountId;
    }

    public function getToAccountId(): string
    {
        return $this->toAccountId;
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