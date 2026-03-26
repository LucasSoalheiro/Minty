<?php

namespace Tests\Domain\Transaction;

use PHPUnit\Framework\TestCase;
use Src\Domain\shared\Money;
use Src\Domain\Transaction\error\InvalidAmount;
use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionEnum;
use Src\Domain\Transaction\TransactionStatusEnum;

class TransactionTest extends TestCase
{
    public function testShouldCreateTransaction(): void
    {
        $transaction = Transaction::create("account123", Money::create(1000), TransactionEnum::INFLOW, "Test transaction", "category123");

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals("account123", $transaction->getAccountId());
        $this->assertEquals(1000, $transaction->getAmount());
        $this->assertEquals(TransactionEnum::INFLOW, $transaction->getType());
        $this->assertEquals("Test transaction", $transaction->getDescription());
        $this->assertEquals("category123", $transaction->getCategoryId());
    }

    public function testShouldNotCreateTransactionWithNegativeAmount(): void
    {
        $this->expectException(InvalidAmount::class);

        Transaction::create("account123", Money::create(-100), TransactionEnum::INFLOW, "Test transaction", "category123");
    }

    public function testShouldNotCreateTransactionWIthInvalidAccountId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Transaction::create("invalid-account-id", Money::create(1000), TransactionEnum::INFLOW, "Test transaction", "category123");
    }

    public function testShouldNotCreateTransactionWithInvalidCategoryId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Transaction::create("account123", Money::create(1000), TransactionEnum::INFLOW, "Test transaction", "invalid-category-id");
    }

    public function testShouldNotCreateTransactionWithInvalidCreatedAt(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $futureDate = new \DateTime();
        $futureDate->modify('+1 day');

        Transaction::restore("account123", Money::create(1000), TransactionEnum::INFLOW, TransactionStatusEnum::DONE, "Test transaction", "category123", $futureDate);
    }
}