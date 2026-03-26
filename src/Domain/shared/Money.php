<?php
namespace Src\Domain\shared;

use Src\Domain\Transaction\error\InvalidAmount;

final class Money
{
    private function __construct(
        // The amount is stored as an integer representing the smallest currency unit (e.g., cents)
        private int $amount
    ) {
    }

    public static function create(int $amount): Money
    {
        if ($amount <= 0) {
            throw new InvalidAmount();
        }
        return new Money($amount);
    }
    public function add(Money $other): Money
    {
        return new Money($this->amount + $other->amount);
    }

    public function subtract(Money $other): Money
    {
        return new Money($this->amount - $other->amount);
    }

    public function value(): int
    {
        return $this->amount;
    }
}