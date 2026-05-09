<?php

namespace Tests\fake;

use Override;
use Src\Domain\Entities\Account;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\ValueObject\UUID;

class FakeAccountRepository implements AccountRepository
{
    /**
     * @var Account[]
     */
    private array $accounts = [];

    public function save(Account $account): void
    {
        $this->accounts[$account->id->__toString()] = $account;
    }

    public function findById(string $id): ?Account
    {
        return $this->accounts[$id] ?? null;
    }

    public function list(string $userId): array
    {
        return array_values(array_filter($this->accounts, fn($a) => $a->userId->equals(UUID::fromString($userId))));
    }
}