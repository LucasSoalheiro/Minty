<?php
namespace Src\Domain\Account;

use Src\Domain\shared\Money;
use Src\Domain\shared\UUID;
class Account
{
    private function __construct(
        private readonly UUID $id,
        private readonly string $name,
        private Money $balance,
        private readonly UUID $userId,
        private  bool $isActive,
    ) {
    }

    public static function create(string $name, Money $balance, UUID $userId): Account
    {
        return new Account(UUID::generate(), $name, $balance, $userId, true);
    }

    public static function restore(UUID $id, string $name, Money $balance, UUID $userId, bool $isActive): Account
    {
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
        $this->balance = $this->balance->add($amount);
    }

    public function withdraw(Money $amount): void
    {
        $this->isActiveAccount();
        if ($this->balance->value() < $amount->value()) {
            throw new \ErrorException("Insufficient funds.");
        }
        $this->balance = $this->balance->subtract($amount);
    }

    public function transfer(Account $toAccount, Money $amount): void
    {
        $this->isActiveAccount();
        $this->withdraw($amount);
        $toAccount->deposit($amount);
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }


    private function isActiveAccount(): void
    {
        if (!$this->isActive) {
            throw new \ErrorException("Account is deactivated.");
        }
    }

}