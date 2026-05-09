<?php

namespace Tests\fake;

use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionStatusEnum;
use Src\Domain\Repository\TransactionRepository;
use Src\Domain\ValueObject\UUID;

final class FakeTransactionRepository implements TransactionRepository
{
    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    public function save(Transaction $transaction): void
    {
        $this->transactions[$transaction->id->__toString()] = $transaction;
    }

    public function list(string $accountId, ?TransactionStatusEnum $status = null): array
    {
        return array_values(array_filter(
            $this->transactions,
            fn($t) =>
            $t->accountId->equals(UUID::fromString($accountId)) &&
            ($status === null || $t->status === $status)
        ));
    }

    public function findById(string $id): ?Transaction
    {
        return $this->transactions[$id] ?? null;
    }

    public function findByAccountId(string $accountId): array
    {
        return array_values(array_filter($this->transactions, fn($t) => $t->accountId->equals(UUID::fromString($accountId))));
    }
}