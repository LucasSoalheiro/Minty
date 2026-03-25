<?php
namespace Src\Domain\Account\repository;

use Src\Domain\Account\Account;

interface AccountRepository
{
    public function save(Account $account): void;
    public function findById(string $id): ?Account;
    public function findByUserId(string $userId): array;
}