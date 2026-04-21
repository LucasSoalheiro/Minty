<?php

namespace Src\Domain\Entities;

use Src\Domain\Error\AccountAlreadyDeactivated;
use Src\Domain\Error\InsufficientFunds;
use Src\Domain\Error\InvalidAmount;
use Src\Domain\Error\InvalidTransfer;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;

final class Account
{
    private function __construct(
        public readonly UUID $id,
        public readonly string $name,
        public private(set) Money $balance,
        public readonly UUID $userId,
        public private(set) bool $isActive,
    ) {
    }

    public static function create(string $name, Money $balance, UUID $userId): Account
    {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new Account(UUID::generate(), $name, $balance, $userId, true);
    }

    public static function restore(
        UUID $id,
        string $name,
        Money $balance,
        UUID $userId,
        bool $isActive
    ): Account {
        return new Account($id, $name, $balance, $userId, $isActive);
    }

    public function deposit(Money $amount): void
    {
        $this->isActiveAccount();
        if ($amount->value() <= 0) {
            throw new InvalidAmount();
        }
        $this->balance = $this->balance->add($amount);
    }

    public function withdraw(Money $amount): void
    {
        if (!$this->balance->greaterOrEqual($amount)) {
            throw new InsufficientFunds();
        }
        $this->balance = $this->balance->subtract($amount);
    }

    public function transfer(Account $toAccount, Money $amount): void
    {
        $this->isActiveAccount();
        $toAccount->isActiveAccount();
        if ($amount->value() <= 0) {
            throw new InvalidAmount();
        }
        if ($toAccount->id->equals($this->id)) {
            throw new InvalidTransfer();
        }
        $this->withdraw($amount);
        try {
            $toAccount->deposit($amount);
        } catch (\Throwable $e) {
            $this->deposit($amount);
            throw $e;
        }
    }

    public function deactivate(): void
    {
        $this->isActiveAccount();
        $this->isActive = false;
    }

    private function isActiveAccount(): void
    {
        if (!$this->isActive) {
            throw new AccountAlreadyDeactivated();
        }
    }

}