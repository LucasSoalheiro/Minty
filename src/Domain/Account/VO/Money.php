<?php
namespace Src\Domain\Account\VO;

final class Money
{
    private  function __construct(
        private int $amount
    ) {
    }

    public static function create(int $amount): Money
    {
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