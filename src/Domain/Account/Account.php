<?php
namespace Src\Domain\Account;

use Src\Domain\Error\AccountAlreadyDeactivated;
use Src\Domain\Error\InsufficientFunds;
use Src\Domain\Error\InvalidAmount;
use Src\Domain\Error\InvalidInitialBalance;
use Src\Domain\Error\InvalidTransfer;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;

class Account
{
    private function __construct(
        private readonly UUID $id,
        private readonly string $name,
        private Money $balance,
        private readonly UUID $userId,
        private bool $isActive,
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

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function getUserId(): UUID
    {
        return $this->userId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
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
        if ($toAccount->getId()->equals($this->id)) {
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