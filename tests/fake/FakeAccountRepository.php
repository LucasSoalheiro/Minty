<?php

namespace Tests\fake;

use Src\Domain\Account\Account;
use Src\Domain\Account\AccountRepository;
use Src\Domain\ValueObject\UUID;

class FakeAccountRepository implements AccountRepository
{
    private array $accounts = [];

    public function save($account): void
    {
        $this->accounts[] = $account;
    }

    public function findById(string $id): ?Account
    {
        foreach ($this->accounts as $account) {
            if ($account->getId()->equals(UUID::fromString($id))) {
                return $account;
            }
        }
        return null;
    }

    public function findByUserId(string $userId): array
    {
        $result = [];
        foreach ($this->accounts as $account) {
            if ($account->getUserId()->equals(UUID::fromString($userId))) {
                $result[] = $account;
            }
        }
        return $result;
    }
}