<?php
namespace Src\Domain\Transaction\Repository;

use Src\Domain\Shared\UUID;
use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionStatusEnum;

interface TransactionRepository
{
    public function save(Transaction $transaction): void;
    public function list(?TransactionStatusEnum $status): array;
    public function findById(UUID $id): ?Transaction;
    public function findByAccountId(string $accountId): array;
}