<?php
namespace Src\Domain\Account;

use Src\Domain\Shared\Money;
use Src\Domain\Shared\UUID;
class Account
{
    private readonly UUID $id;
    private readonly bool $isActive;
    private function __construct(
        private readonly string $name,
        private Money $balance,
        private readonly string $userId,
    ) {
        $this->id = UUID::generate();
        $this->isActive = true;
    }

    public static function create(string $name, Money $balance, string $userId): Account
    {
        return new Account($name, $balance, $userId);
    }

    public static function restore(string $name, Money $balance, string $userId): Account
    {
        return new Account($name, $balance, $userId);
    }

    public function getId(): string
    {
        return $this->id->__toString();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function getUserId(): string
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