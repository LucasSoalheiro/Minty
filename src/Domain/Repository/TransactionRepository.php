<?php
namespace Src\Domain\Repository;

use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionStatusEnum;

interface TransactionRepository
{
    public function save(Transaction $transaction): void;
    public function list(string $accountId, ?TransactionStatusEnum $status = null): array;
    public function findById(string $id): ?Transaction;
    public function findByAccountId(string $accountId): array;
}