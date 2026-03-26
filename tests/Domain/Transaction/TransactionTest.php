<?php

namespace Tests\Domain\Transaction;

use PHPUnit\Framework\TestCase;
use Src\Domain\Error\InvalidAmount;
use Src\Domain\Error\InvalidCreatedAt;
use Src\Domain\Error\TransactionAlreadyCancelled;
use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionEnum;
use Src\Domain\Transaction\TransactionStatusEnum;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;

class TransactionTest extends TestCase
{
    public function testShouldCreateTransaction(): void
    {
        $accountId = UUID::generate();
        $categoryId = UUID::generate();
        $transaction = Transaction::create($accountId, Money::create(1000), TransactionEnum::INFLOW, "Test transaction", $categoryId);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertTrue($transaction->getAccountId()->equals($accountId));
        $this->assertEquals(1000, $transaction->getAmount());
        $this->assertEquals(TransactionEnum::INFLOW, $transaction->getType());
        $this->assertEquals("Test transaction", $transaction->getDescription());
        $this->assertTrue($transaction->getCategoryId()->equals($categoryId));
    }

    public function testShouldNotCreateTransactionWithNegativeAmount(): void
    {
        $this->expectException(InvalidAmount::class);
        

        Transaction::create(UUID::generate(), Money::create(-100), TransactionEnum::INFLOW, "Test transaction", UUID::generate());
    }

    public function testShouldNotRestoreTransactionWithInvalidCreatedAt(): void
    {
        $this->expectException(InvalidCreatedAt::class);

        $futureDate = new \DateTime();
        $futureDate->modify('+1 day');

        Transaction::restore(UUID::generate(), UUID::generate(), Money::create(1000), TransactionEnum::INFLOW, TransactionStatusEnum::DONE, "Test transaction", UUID::generate(), $futureDate);
    }

    public function testShouldCancelTransaction(): void
    {
        $transaction = Transaction::create(UUID::generate(), Money::create(1000), TransactionEnum::INFLOW, "Test transaction", UUID::generate());
        $transaction->cancel();

        $this->assertEquals(TransactionStatusEnum::CANCELLED, $transaction->getStatus());
    }

    public function testShouldNotCancelAlreadyCancelledTransaction(): void
    {
        $this->expectException(TransactionAlreadyCancelled::class);

        $transaction = Transaction::create(UUID::generate(), Money::create(1000), TransactionEnum::INFLOW, "Test transaction", UUID::generate());
        $transaction->cancel();
        $transaction->cancel();
    }
}