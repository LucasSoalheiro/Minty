<?php
namespace Src\Domain\Repository;

use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionStatusEnum;

interface TransactionRepository
{
    public function save(Transaction $transaction): void;
    /** @return Transaction[] */
    public function list(string $accountId, ?TransactionStatusEnum $status = null): array;
    public function findById(string $id): ?Transaction;
    /** @return Transaction[] */
    public function findByAccountId(string $accountId): array;
}