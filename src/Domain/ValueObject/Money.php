<?php
namespace Src\Domain\ValueObject;

use Src\Domain\Error\InvalidAmount;


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

    public function greaterOrEqual(Money $amount): bool
    {
        return $this->amount >= $amount->amount;
    }
}