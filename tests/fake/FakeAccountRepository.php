<?php

namespace Tests\fake;

use Src\Domain\Entities\Account;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\ValueObject\UUID;

class FakeAccountRepository implements AccountRepository
{
    /**
     * @var Account[]
     */
    private array $accounts = [];

    public function save($account): void
    {
        $this->accounts[] = $account;
    }

    public function findById(string $id): ?Account
    {
        return array_find($this->accounts, fn($a) => $a->id->equals(UUID::fromString($id)));
    }

    public function findByUserId(string $userId): array
    {
        return array_filter($this->accounts, fn($a) => $a->userId->equals(UUID::fromString($userId)));
    }
}