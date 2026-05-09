<?php

namespace Src\Infra\Db\Mapper;

use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionEnum;
use Src\Domain\Entities\TransactionStatusEnum;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\TransactionEntity;

class TransactionMapper
{
    public static function toDomain(
        string $id,
        string $accountId,
        int $amount,
        \DateTime $createdAt,
        string $type,
        string $status,
        ?string $description,
        string $categoryId
    ): Transaction {
        return Transaction::restore(
            UUID::fromString($id),
            UUID::fromString($accountId),
            Money::create($amount),
            constant(TransactionEnum::class . "::$type"),
            constant(TransactionStatusEnum::class . "::$status"),
            $description,
            UUID::fromString($categoryId),
            $createdAt
        );
    }

    public static function toPersistence(Transaction $domain): TransactionEntity
    {
        $entity = new TransactionEntity();
        $entity->setId($domain->id->__toString());
        $entity->setAccountId($domain->accountId->__toString());
        $entity->setAmount($domain->amount->value());
        $entity->setCreatedAt($domain->createdAt);
        $entity->setType($domain->type->name);
        $entity->setStatus($domain->status->name);
        $entity->setDescription($domain->description);
        $entity->setCategoryId($domain->categoryId->__toString());
        return $entity;
    }

    public static function updatePersistence(TransactionEntity $entity, Transaction $domain): void
    {
        $entity->setStatus($domain->status->name);
    }
}
