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
        $this->transactions[] = $transaction;
    }

    public function list(string $accountId, ?TransactionStatusEnum $status = null): array
    {
        return array_filter(
            $this->transactions,
            fn($t) =>
            $t->accountId->equals(UUID::fromString($accountId)) &&
            $t->status === $status
        );
    }

    public function findById(string $id): ?Transaction
    {
        return array_find($this->transactions, fn($t) => $t->id->equals(UUID::fromString($id)));
    }

    public function findByAccountId(string $accountId): array
    {
        return array_filter($this->transactions, fn($t) => $t->accountId->equals(UUID::fromString($accountId)));
    }
}