<?php

namespace Tests\fake;

use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionRepository;
use Src\Domain\Transaction\TransactionStatusEnum;
use Src\Domain\ValueObject\UUID;

class FakeTransactionRepository implements TransactionRepository
{
    private array $transactions = [];

    public function save(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function list(string $accountId, ?TransactionStatusEnum $status = null): array
    {
        $result = [];
        foreach ($this->transactions as $transaction) {
            if ($transaction->getAccountId()->equals(UUID::fromString($accountId))) {
                if ($status === null || $transaction->getStatus() === $status) {
                    $result[] = $transaction;
                }
            }
        }
        return $result;
    }

    public function findById(string $id): ?Transaction
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->getId()->equals(UUID::fromString($id))) {
                return $transaction;
            }
        }
        return null;
    }

    public function findByAccountId(string $accountId): array
    {
        $result = [];
        foreach ($this->transactions as $transaction) {
            if ($transaction->getAccountId()->equals(UUID::fromString($accountId))) {
                $result[] = $transaction;
            }
        }
        return $result;
    }
}