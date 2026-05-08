<?php

namespace Src\Infra\Db\Mapper;

use Src\Domain\Entities\Account;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\AccountEntity;

class AccountMapper
{
    public static function toDomain(
        string $id,
        string $name,
        int $balance,
        string $userId,
        bool $isActive
    ): Account {
        return Account::restore(
            UUID::fromString($id),
            $name,
            Money::create($balance),
            UUID::fromString($userId),
            $isActive
        );
    }

    public static function toPersistence(Account $domain): AccountEntity
    {
        $entity = new AccountEntity();
        $entity->setId($domain->id->__toString());
        $entity->setName($domain->name);
        $entity->setBalance($domain->balance->value());
        $entity->setUserId($domain->userId->__toString());
        $entity->setIsActive($domain->isActive);
        return $entity;
    }

    public static function updatePersistence(AccountEntity $entity, Account $domain): void
    {
        $entity->setName($domain->name);
        $entity->setBalance($domain->balance->value());
        $entity->setIsActive($domain->isActive);
    }
}
