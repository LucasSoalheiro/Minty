<?php

namespace Src\App\DTO;

class CreateAccountDto
{
    public function __construct(
        private string $name,
        private int $balance,
        private string $userId
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}