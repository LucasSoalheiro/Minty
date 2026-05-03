<?php
namespace Src\Domain\Repository;

use Src\Domain\Entities\Account;

interface AccountRepository
{
    public function save(Account $account): void;
    public function findById(string $id): ?Account;
    /** @return Account[] */
    public function list(string $userId): array;
}