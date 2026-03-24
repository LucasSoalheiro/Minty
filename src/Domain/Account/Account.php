<?php
namespace Src\Domain\Account;

use Src\Domain\Account\VO\Money;
use Src\Domain\Util\UUID;
class Account
{
    private readonly UUID $id = UUID::generate();
    private readonly bool $isActive = true;
    private function __construct(
        private readonly string $name,
        private Money $balance,
        private readonly string $userId,
    ) {
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